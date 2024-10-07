<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{
    public function store(Request $request) {

        // Validate the form data
        $request->validate([
            'claim_company' => 'required|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'remarks' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        // Get the authenticated user to refer to the user_id
        $user = Auth::user();

        // Create a new claim
        $claim = new Claim();
        
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
    
        // Title will depends on the user ID and claim ID
        $claim->title = '[' . $claim->claim_id . '] ' .'Petrol Claim - ' . $user->first_name;

        // 2nd Save the updated claim to the database
        // To allow title to be updated based on the user ID and claim ID
        $claim->save();

        // Redirect to the home page with a success message
        return redirect()->route('home')->with('success', 'Claim submitted successfully.');


    }
}
