<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimDocument;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClaimController extends Controller
{


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
    

    public function store(Request $request) {

        // Log the start of the store method
        Log::info('Starting the store method in ClaimController');

        // Validate the form data
        $validator = Validator::make($request->all(), [
            'claim_company' => 'required|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'remarks' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'toll_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'email_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        Log::info('Request data', ['request' => $request->all()]);

        if ($validator->fails()) {
            Log::info('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Start a database transaction
        DB::beginTransaction();
        
        try {

            // Get the authenticated user to refer to the user_id
            $user = Auth::user();

            // Create a new claim
            $claim = new Claim();

            Log::info('Creating a new claim');Log::info('Creating a new claim');
            
            // Mileage Total Amount Calculation
            $totalDistance = $request->input('total_distance_input');
            $totalAmount = $totalDistance * 0.6;

            // Claim company based on the claim_company input
            $claim_company = $request->input('claim_company');
            switch (true) {
                case 'Malaysia Heritage Studios':
                case 'Pusat Sains Kreativiti Terengganu':
                case 'Zoo Melaka':
                    $claim_company = 'WGE';
                    break;
                case 'Zoo Teruntum':
                case 'Silverlake Outlet Mall':
                case 'Wegrow Global Sdn Bhd':
                    $claim_company = 'WGG';
                    break;
                default:
                    $claim_company = 'Unknown';
            }

            /////////////////////////////////////////
            //// Value Assignment to Each Column ////
            /////////////////////////////////////////

            // User ID assignment (FK)
            $claim->user_id = $user->id;

            // Temporary title
            $claim->title = 'TEMP';

            // Others columns assignment
            $claim->description = $request->input('remarks');
            $claim->amount = $totalAmount;
            $claim->status = 'Submitted';
            $claim->claim_type = 'Petrol';
            $claim->submitted_at = now();
            $claim->claim_company = $claim_company;
            $claim->toll_amount = $request->input('toll_amount');
            $claim->from_location = $request->input('origin');
            $claim->to_location = $request->input('destination');
            $claim->date_from = $request->input('date_from');
            $claim->date_to = $request->input('date_to');
            
            // 1st Save Claim to Database
            $claim->save();
            Log::info('Claim saved', ['claim_id' => $claim->id]);
    
            // Title will depends on the user ID and claim ID
            $claim->title = 'Petrol Claim - ' . $request->input('claim_company');

            // 2nd Save the updated claim to the database
            // To allow title to be updated based on the user ID and claim ID
            $claim->save();


            // Create a claim directory for the user
            Storage::disk('public')->makeDirectory('uploads/claims/toll');
            Storage::disk('public')->makeDirectory('uploads/claims/email');

            // Handle toll file upload
            $tollFileName = '';
            $tollFilePath = '';

            if ($request->hasFile('toll_report')) {
                $tollFile = $request->file('toll_report');
                $tollFileName = time() . '_toll_' . $tollFile->getClientOriginalName();
                $tollFilePath = $tollFile->storeAs('uploads/claims/toll', $tollFileName, 'public');
                Log::info('Toll report uploaded', ['file_name' => $tollFileName]);
            }

            // Handle email file upload
            $emailFileName = '';
            $emailFilePath = '';

            if ($request->hasFile('email_report')) {
                $emailFile = $request->file('email_report');
                $emailFileName = time() . '_email_' . $emailFile->getClientOriginalName();
                $emailFilePath = $emailFile->storeAs('uploads/claims/email', $emailFileName, 'public');
                Log::info('Email report uploaded', ['file_name' => $emailFileName]);
            }

            // Create a claim document
            ClaimDocument::create([
                'claim_id' => $claim->claim_id,
                'toll_file_name' => $tollFileName,
                'toll_file_path' => $tollFilePath,
                'email_file_name' => $emailFileName,
                'email_file_path' => $emailFilePath,
                'uploaded_by' => Auth::id(),
            ]);

            // Commit the transaction
            DB::commit();
            Log::info('Transaction committed successfully');

            // Redirect to the home page with a success message
            return redirect()->route('home')->with('success', 'Claim and documents submitted successfully.');
        
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            Log::error('Error occurred in store method', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while submitting the claim.');
        }
    }
}
