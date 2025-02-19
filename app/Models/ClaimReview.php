<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'reviewer_id',
        'remarks',
        'review_order',
        'department',
        'reviewed_at',
        'status'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
