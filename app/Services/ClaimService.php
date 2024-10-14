<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Claim;
use App\Models\ClaimDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Str;

class ClaimService
{

    //////////////////////////////////////////////////////////////////

    private const PETROL_RATE = 0.6;
    private const CLAIM_TYPE_PETROL = 'Petrol';
    private const CLAIM_TYPE_ITEMS = 'Items';
    private const CLAIM_TYPE_PETTY_CASH = 'Petty Cash';
    private const CLAIM_TYPE_OTHERS = 'Others';
    private const STATUS_SUBMITTED = 'Submitted';

    //////////////////////////////////////////////////////////////////

    public function __construct()
    {

    }

    //////////////////////////////////////////////////////////////////

    public function buildClaim(array $data, User $user, $totalAmount)
    {
        $claim = new Claim();
        $claim->user_id = $user->id;
        $claim->title = 'TEMP';
        $claim->description = $data['remarks'];
        $claim->petrol_amount = $totalAmount;
        $claim->status = self::STATUS_SUBMITTED;
        $claim->claim_type = self::CLAIM_TYPE_PETROL;
        $claim->submitted_at = now();
        $claim->claim_company = strtoupper($data['claim_company']);
        $claim->toll_amount = $data['toll_amount'];
        $claim->total_distance = number_format((float)$data['total_distance'], 2, '.', '');
        $claim->from_location = $data['location'][0] ?? null;
        $claim->to_location = end($data['location']) ?? null;
        $claim->date_from = $data['date_from'];
        $claim->date_to = $data['date_to'];
        $claim->token = \Illuminate\Support\Str::random(32);

        return $claim;
    }
    //////////////////////////////////////////////////////////////////

    public function updateClaimStatus(Claim $claim, string $status)
    {
        $claim->status = $status;
        $claim->save();
        return $claim;
    }

    //////////////////////////////////////////////////////////////////

    private function createLocations(Claim $claim, array $locations)
    {
        foreach ($locations as $index => $location) {
            $claim->locations()->create([
                'location' => $location,
                'order' => $index + 1,
                'claim_id' => $claim->id,
            ]);
        }
    }

    //////////////////////////////////////////////////////////////////

    public function handleFileUploadsAndDocuments($claim, $tollReport, $emailReport)
    {
        Storage::disk('public')->makeDirectory('uploads/claims/toll/');
        Storage::disk('public')->makeDirectory('uploads/claims/email');

        $tollFileInfo = $this->uploadFile($tollReport, 'uploads/claims/toll', 'toll');
        $emailFileInfo = $this->uploadFile($emailReport, 'uploads/claims/email', 'email');

        ClaimDocument::create([
            'claim_id' => $claim->id,
            'toll_file_name' => $tollFileInfo['fileName'],
            'toll_file_path' => $tollFileInfo['filePath'],
            'email_file_name' => $emailFileInfo['fileName'],
            'email_file_path' => $emailFileInfo['filePath'],
            'uploaded_by' => Auth::id(),
        ]);

        return $claim;
    }

    //////////////////////////////////////////////////////////////////

    private function uploadFile($file, $path, $prefix)
    {
        if (!$file) {
            return ['fileName' => '', 'filePath' => ''];
        }

        $fileName = time() . "_{$prefix}_" . $file->getClientOriginalName();
        $filePath = $file->storeAs($path, $fileName, 'public');
        Log::info("{$prefix} report uploaded", ['file_name' => $fileName]);

        return ['fileName' => $fileName, 'filePath' => $filePath];
    }

    //////////////////////////////////////////////////////////////////

    private function calculateTotalAmount($totalDistance)
    {
        return $totalDistance * self::PETROL_RATE;
    }

    //////////////////////////////////////////////////////////////////

    public function createClaim(array $data, User $user)
    {
        Log::info('ClaimService@createClaim method called', ['data' => $data]);

        try {
            $totalDistance = $data['total_distance'];
            $totalAmount = $this->calculateTotalAmount($totalDistance);

            $claim = $this->buildClaim($data, $user, $totalAmount);
            $claim->save();

            $this->createLocations($claim, $data['location']);

            $claim->title = 'Petrol Claim - ' . $claim->claim_company;
            $claim->save();

            Log::info('Claim saved', ['id' => $claim->id]);

            return $claim;

        } catch (\Exception $e) {

            Log::info('Error creating claim: ' . $e->getMessage());
            throw $e;

        }
    }

    //////////////////////////////////////////////////////////////////

    public function getClaimsBasedOnRole(User $user)
    {
        switch ($user->role->name) {
            case 'admin':
                return Claim::with('user')->whereIn('status', [Claim::STATUS_SUBMITTED, Claim::STATUS_APPROVED_ADMIN])->get();
            case 'hr':
                return Claim::with('user')->where('status', Claim::STATUS_APPROVED_DATUK)->get();
            case 'finance':
                return Claim::with('user')->whereIn('status', [Claim::STATUS_APPROVED_HR, Claim::STATUS_APPROVED_FINANCE])->get();
            default:
                return Claim::with('user')->where('user_id', $user->id)->get();
        }
    }

    /////////////////////////////////////////////////////////////////

    public function canReviewClaim(User $user, Claim $claim)
    {
        switch ($user->role->name) {
            case 'Admin':
                return in_array($claim->status, [Claim::STATUS_SUBMITTED, Claim::STATUS_APPROVED_ADMIN]);
            case 'HR':
                return $claim->status === Claim::STATUS_APPROVED_DATUK;
            case 'Finance':
                return in_array($claim->status, [Claim::STATUS_APPROVED_HR, Claim::STATUS_APPROVED_FINANCE]);
            default:
                return false;
        }
    }

    //////////////////////////////////////////////////////////////////

    public function approveClaim(User $user, Claim $claim)
    {
        switch ($user->role->name) {
            case 'admin':
                if ($claim->status === Claim::STATUS_SUBMITTED) {
                    $claim->status = Claim::STATUS_APPROVED_ADMIN;
                } elseif ($claim->status === Claim::STATUS_APPROVED_ADMIN) {
                    $claim->status = Claim::STATUS_APPROVED_DATUK;
                }
                break;
            case 'hr':
                $claim->status = Claim::STATUS_APPROVED_HR;
                break;
            case 'finance':
                if ($claim->status === Claim::STATUS_APPROVED_HR) {
                    $claim->status = Claim::STATUS_APPROVED_FINANCE;
                } elseif ($claim->status === Claim::STATUS_APPROVED_FINANCE) {
                    $claim->status = Claim::STATUS_DONE;
                }
                break;
        }
        $claim->save();
        return $claim;
    }

    //////////////////////////////////////////////////////////////////

}