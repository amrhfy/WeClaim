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

    protected $log;
    private const PETROL_RATE = 0.6;
    private const CLAIM_TYPE_PETROL = 'Petrol';
    private const CLAIM_TYPE_ITEMS = 'Items';
    private const CLAIM_TYPE_PETTY_CASH = 'Petty Cash';
    private const CLAIM_TYPE_OTHERS = 'Others';
    private const STATUS_SUBMITTED = 'Submitted';

    //////////////////////////////////////////////////////////////////

    public function __construct(Log $log)
    {
        $this->log = $log;
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
        $claim->total_distance = $data['total_distance_input'];
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
        Storage::disk('public')->makeDirectory('uploads/claims/toll');
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
        $this->log->info("{$prefix} report uploaded", ['file_name' => $fileName]);

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
        $this->log->info('ClaimService@createClaim method called', ['data' => $data]);

        try {
            $totalDistance = $data['total_distance_input'];
            $totalAmount = $this->calculateTotalAmount($totalDistance);

            $claim = $this->buildClaim($data, $user, $totalAmount);
            $claim->save();

            $this->createLocations($claim, $data['location']);

            $claim->title = 'Petrol Claim - ' . $claim->claim_company;
            $claim->save();

            $this->log->info('Claim saved', ['id' => $claim->id]);

            return $claim;

        } catch (\Exception $e) {

            $this->log->error('Error creating claim: ' . $e->getMessage());
            throw $e;

        }
    }

    //////////////////////////////////////////////////////////////////

}