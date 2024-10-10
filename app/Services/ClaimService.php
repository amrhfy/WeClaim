<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Claim;
use App\Models\ClaimDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClaimService
{
    public function createClaim(array $data, User $user)
    {
        $totalDistance = $data['total_distance_input'];
        $totalAmount = $this->calculateTotalAmount($totalDistance);
        $claim_company = $this->determineClaimCompany($data['claim_company']);

        $claim = new Claim();
        $claim->user_id = $user->id;
        $claim->title = 'TEMP';
        $claim->description = $data['remarks'];
        $claim->amount = $totalAmount;
        $claim->status = 'Submitted';
        $claim->claim_type = 'Petrol';
        $claim->submitted_at = now();
        $claim->claim_company = $claim_company;
        $claim->toll_amount = $data['toll_amount'];
        $claim->from_location = $data['origin'];
        $claim->to_location = $data['destination'];
        $claim->date_from = $data['date_from'];
        $claim->date_to = $data['date_to'];
        $claim->token = \Illuminate\Support\Str::random(32);

        $claim->save();

        $claim->title = 'Petrol Claim' . $data['claim_company'];
        $claim->save();

        Log::info('Claim saved', ['claim_id' => $claim->claim_id]);

        return $claim;
    }

    /*
    
    Create File Handling Uploads and Documents
    
    */

    public function handleFileUploadsAndDocuments($claim, $tollReport, $emailReport){
        Storage::disk('public')->makeDirectory('uploads/claims/toll');
        Storage::disk('public')->makeDirectory('uploads/claims/email');

        $tollFileName = '';
        $tollFilePath = '';
        $emailFileName = '';
        $emailFilePath = '';

        if ($tollReport) {
            $tollFileName = time() . '_toll_' . $tollReport->getClientOriginalName();
            $tollFilePath = $tollReport->storeAs('uploads/claims/toll', $tollFileName, 'public');
            Log::info('Toll report uploaded', ['file_name' => $tollFileName]);
        }

        if ($emailReport) {
            $emailFileName = time() . '_email_' . $emailReport->getClientOriginalName();
            $emailFilePath = $emailReport->storeAs('uploads/claims/email', $emailFileName, 'public');
            Log::info('Email report uploaded', ['file_name' => $emailFileName]);
        }

        ClaimDocument::create([
            'claim_id' => $claim->claim_id,
            'toll_file_name' => $tollFileName,
            'toll_file_path' => $tollFilePath,
            'email_file_name' => $emailFileName,
            'email_file_path' => $emailFilePath,
            'uploaded_by' => Auth::id(),
        ]);
    
        return $claim;
    }

    /*
    
    Calculate Total Amount Function
    
    */

    private function calculateTotalAmount($totalDistance)
    {
        return $totalDistance * 0.6;
    }

    /*
    
    Determine Claim Company Function
    
    */

    private function determineClaimCompany($inputCompany)
    {
        switch ($inputCompany) {
            case 'Malaysia Heritage Studios':
            case 'Pusat Sains Kreativiti Terengganu':
            case 'Zoo Melaka':
                return 'WGE';
            case 'Zoo Teruntum':
            case 'Silverlake Outlet Mall':
            case 'Wegrow Global Sdn Bhd':
                return 'WGG';
            default:
                return 'Unknown';
        }
    }
}
