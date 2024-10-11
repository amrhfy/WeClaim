<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Models\Claim;
use App\Mail\ClaimActionMail;
use App\Services\ClaimService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ClaimController extends Controller
{

    //////////////////////////////////////////////////////////////////

    protected $claimService;
    protected $mail;

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
            $claims = Claim::with('user')->where('user_id', Auth::id())->get();
            return view($view, compact('claims'));
        }
        
        return redirect()->route('login');
    }

    //////////////////////////////////////////////////////////////////

    public function show($id, $view)
    {
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

                $this->mail->to(self::ADMIN_EMAIL)->send(new ClaimActionMail($claim));
            });

            return redirect()->route('claims-dashboard')->with('success', 'Claim submitted successfully!');

        } catch (\Exception $e) {

            Log::error('Error submitting claim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting the claim. Please try again.');

        }
    }

    //////////////////////////////////////////////////////////////////
}
