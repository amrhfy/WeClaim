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
    protected $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }


    public function index($view)
    {
        if (Auth::check()) {
            $claims = Claim::with('user')->where('user_id', Auth::id())->get();
            return view($view, compact('claims'));
        }
        
        return redirect()->route('login');
    }

    public function show($id, $view)
    {
        $claim = Claim::findOrFail($id);
        return view($view, compact('claim'));
    }
    

    public function approve($token)
    {
        $claim = Claim::where('token', $token)->firstOrFail();
        $claim->status = 'Approved';
        $claim->save();

        return redirect()->route('claims.index')->with('status', 'Claim approved successfully!');
    }

    public function reject($token)
    {
        $claim = Claim::where('token', $token)->firstOrFail();
        $claim->status = 'Rejected';
        $claim->save();

        return redirect()->route('claims.index')->with('status', 'Claim rejected successfully!');
    }

    public function store(StoreClaimRequest $request)
    {
        Log::info('Starting the store method in ClaimController');
        Log::info('Request data', ['request' => $request->all()]);
        
        $validatedData = $request->validated();
        $user = Auth::user();

        DB::transaction(function () use ($validatedData, $user, $request) {
            $claim = $this->claimService->createClaim($validatedData, $user);
            $claim = $this->claimService->handleFileUploadsAndDocuments($claim, $request->file('toll_report'), $request->file('email_report'));

            Log::info('Attempting to send email for claim ID: ' . $claim->claim_id);
            Mail::to('ammar@wegrow-global.com')->send(new ClaimActionMail($claim));
            Log::info('Email sent for claim ID: ' . $claim->claim_id);
        });

        return redirect()->route('claims.index')->with('success', 'Claim submitted successfully!');
    }



}
