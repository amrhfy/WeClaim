<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use App\Mail\ClaimActionMail;
use App\Models\ClaimLocation;
use App\Services\ClaimService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;


class ClaimController extends Controller
{

    //////////////////////////////////////////////////////////////////

    protected $claimService;
    use AuthorizesRequests;

    private const ADMIN_EMAIL = 'admin@wegrow-global.com';
    private const HR_EMAIL = 'hr@wegrow-global.com';
    private const FINANCE_EMAIL = 'finance@wegrow-global.com';
    private const TEST_EMAIL = 'ammar@wegrow-global.com';

    //////////////////////////////////////////////////////////////////

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }


    //////////////////////////////////////////////////////////////////

    public function index($view)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $claims = Claim::with('user')->where('user_id', Auth::id())->get();
            return view($view, compact('claims'));
        }
        
        return redirect()->route('login');
    }

    //////////////////////////////////////////////////////////////////

    public function show($id, $view)
    {
        $claim = Claim::findOrFail($id);
        return view($view, compact('claim'));
    }

    //////////////////////////////////////////////////////////////////

    public function store(StoreClaimRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {
                Log::info('Starting claim submission process');

                $validatedData = $request->validated();
                $user = Auth::user();
                $claim = $this->claimService->createClaim($validatedData, $user);
                $claim = $this->claimService->handleFileUploadsAndDocuments($claim, $request->file('toll_report'), $request->file('email_report'));

                Mail::to(self::ADMIN_EMAIL)->send(new ClaimActionMail($claim));
            });

            return redirect()->route('claims.dashboard')->with('success', 'Claim submitted successfully!');

        } catch (\Exception $e) {

            Log::error('Error submitting claim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting the claim. Please try again.');

        }
    }

    //////////////////////////////////////////////////////////////////

    
    public function approvalScreen()
    {
        Log::info('Approval screen accessed by user: ' . Auth::id());

        $claims = Claim::with('user')->get();
        
        $user = Auth::user();
        if ($user->role->name === 'staff') {
            return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
        }
    
        $claims = $this->claimService->getClaimsBasedOnRole($user);
        return view('claims.approval', compact('claims'), ['claims' => $claims, 'claimService' => $this->claimService]);
    }

    //////////////////////////////////////////////////////////////////


    public function reviewClaim($id)
    {
        $user = Auth::user();
        $claim = Claim::with('locations')->findOrFail($id);

        if (!$this->claimService->canReviewClaim($user, $claim)) {
            return redirect()->route('claims.approval')->with('error', 'You do not have permission to review this claim.');
        }
    

        return view('claims.review', compact('claim'));
    }

    //////////////////////////////////////////////////////////////////

    
    public function approveClaim($id)
    {
        $user = Auth::user();
        $claim = Claim::findOrFail($id);

        if (!$this->claimService->canReviewClaim($user, $claim)) {
            return redirect()->route('claims.approval')->with('error', 'You do not have permission to approve this claim.');
        }

        $updatedClaim = $this->claimService->approveClaim($user, $claim);

        // Send email notifications based on the new status
        switch ($updatedClaim->status) {
            case Claim::STATUS_APPROVED_ADMIN:
                Mail::to(self::ADMIN_EMAIL)->send(new ClaimActionMail($updatedClaim));
                break;
            case Claim::STATUS_APPROVED_DATUK:
                Mail::to(self::HR_EMAIL)->send(new ClaimActionMail($updatedClaim));
                break;
            case Claim::STATUS_APPROVED_HR:
            case Claim::STATUS_APPROVED_FINANCE:
            case Claim::STATUS_DONE:
                Mail::to(self::FINANCE_EMAIL)->send(new ClaimActionMail($updatedClaim));
                break;
        }

        return redirect()->route('claims.approval')->with('success', 'Claim approved successfully.');
    }

    public function viewDocument(Claim $claim, $type)
    {

        $document = $claim->documents()->first();
    
        if (!$document) {
            abort(404, 'Document not found');
        }
    
        $filePath = storage_path('app/public/' . $document->{$type . '_file_path'});
    
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
    
        return response()->file($filePath);
    }
    
    

    //////////////////////////////////////////////////////////////////
}
