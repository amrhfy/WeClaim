<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Claim;
use App\Models\ClaimDocument;
<<<<<<< HEAD
use App\Models\ClaimReview;
=======
>>>>>>> origin/main
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

<<<<<<< HEAD
    public function createOrUpdateClaim(array $data, User $user, $claimId = null)
    {
        if ($claimId) {
            $claim = Claim::findOrFail($claimId);
            $claim->update($this->prepareClaim($data, $user));
            $claim->status = $this->getPreviousNonRejectedStatus($claim) ?? Claim::STATUS_SUBMITTED;
        } else {
            $claim = new Claim($this->prepareClaim($data, $user));
        }

        $claim->save();
        $this->createOrUpdateLocations($claim, $data['location']);

        return $claim;
    }

    //////////////////////////////////////////////////////////////////

    private function prepareClaim(array $data, User $user)
    {
        return [
            'user_id' => $user->id,
            'title' => 'Petrol Claim - ' . strtoupper($data['claim_company']),
            'description' => $data['remarks'],
            'petrol_amount' => $this->calculateTotalAmount($data['total_distance']),
            'status' => self::STATUS_SUBMITTED,
            'claim_type' => self::CLAIM_TYPE_PETROL,
            'submitted_at' => now(),
            'claim_company' => strtoupper($data['claim_company']),
            'toll_amount' => $data['toll_amount'],
            'total_distance' => $data['total_distance'],
            'from_location' => $data['location'][0] ?? null,
            'to_location' => end($data['location']) ?? null,
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'token' => \Illuminate\Support\Str::random(32),
        ];
    }

    //////////////////////////////////////////////////////////////////

    private function createOrUpdateLocations(Claim $claim, array $locations)
    {
        $claim->locations()->delete();
        foreach ($locations as $index => $location) {
            $claim->locations()->create([
                'location' => $location,
                'order' => $index + 1,
                'claim_id' => $claim->id,
            ]);
        }
    }

=======
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
>>>>>>> origin/main
    //////////////////////////////////////////////////////////////////

    public function updateClaimStatus(Claim $claim, string $status)
    {
        $claim->status = $status;
        $claim->save();
        return $claim;
    }

    //////////////////////////////////////////////////////////////////

<<<<<<< HEAD
=======
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

>>>>>>> origin/main
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

<<<<<<< HEAD
=======
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

>>>>>>> origin/main
    public function getClaimsBasedOnRole(User $user)
    {
        switch ($user->role->name) {
            case 'admin':
<<<<<<< HEAD
                return Claim::with('user')->whereIn('status', [Claim::STATUS_SUBMITTED, Claim::STATUS_APPROVED_ADMIN, Claim::STATUS_REJECTED])->get();
            case 'hr':
                return Claim::with('user')->whereIn('status', [Claim::STATUS_APPROVED_DATUK, Claim::STATUS_REJECTED])->get();
            case 'finance':
                return Claim::with('user')->whereIn('status', [Claim::STATUS_APPROVED_HR, Claim::STATUS_APPROVED_FINANCE, Claim::STATUS_REJECTED])->get();
=======
                return Claim::with('user')->whereIn('status', [Claim::STATUS_SUBMITTED, Claim::STATUS_APPROVED_ADMIN])->get();
            case 'hr':
                return Claim::with('user')->where('status', Claim::STATUS_APPROVED_DATUK)->get();
            case 'finance':
                return Claim::with('user')->whereIn('status', [Claim::STATUS_APPROVED_HR, Claim::STATUS_APPROVED_FINANCE])->get();
>>>>>>> origin/main
            default:
                return Claim::with('user')->where('user_id', $user->id)->get();
        }
    }

    /////////////////////////////////////////////////////////////////

    public function canReviewClaim(User $user, Claim $claim)
    {
<<<<<<< HEAD
        $user = Auth::user();
=======
>>>>>>> origin/main
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
<<<<<<< HEAD
            case 'Admin':
=======
            case 'admin':
>>>>>>> origin/main
                if ($claim->status === Claim::STATUS_SUBMITTED) {
                    $claim->status = Claim::STATUS_APPROVED_ADMIN;
                } elseif ($claim->status === Claim::STATUS_APPROVED_ADMIN) {
                    $claim->status = Claim::STATUS_APPROVED_DATUK;
                }
                break;
<<<<<<< HEAD
            case 'HR':
                $claim->status = Claim::STATUS_APPROVED_HR;
                break;
            case 'Finance':
=======
            case 'hr':
                $claim->status = Claim::STATUS_APPROVED_HR;
                break;
            case 'finance':
>>>>>>> origin/main
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

<<<<<<< HEAD
    public function rejectClaim(User $user, Claim $claim)
    {
        $claim->status = Claim::STATUS_REJECTED;
        $claim->save();
        return $claim;
    }

    public function storeRemarks(User $user, Claim $claim, string $remarks)
    {
        $reviewOrder = ClaimReview::where('claim_id', $claim->id)
            ->where('department', $user->role->name)
            ->count() + 1;
    
        $claimReview = new ClaimReview([
            'claim_id' => $claim->id,
            'reviewer_id' => $user->id,
            'remarks' => $remarks,
            'review_order' => $reviewOrder,
            'department' => $user->role->name,
            'reviewed_at' => now(),
        ]);
    
        $claimReview->save();
    }

    private function getPreviousNonRejectedStatus(Claim $claim)
    {
        $lastReview = $claim->reviews()
        ->where('status', '!=', Claim::STATUS_REJECTED)
        ->orderBy('created_at', 'desc')
        ->first();

        return $lastReview ? $lastReview->status : null;
    }

    private function getReviewColumnForRole(string $roleName): string
    {
        switch ($roleName) {
            case 'Admin':
                return 'remarks_admin';
            case 'HR':
                return 'remarks_hr';
            case 'Finance':
                return 'remarks_finance';
            default:
                return 'remarks_admin';
        }
    }

=======
>>>>>>> origin/main
}