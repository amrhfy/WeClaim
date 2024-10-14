<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;
    protected $dates = ['submitted_at', 'date_from', 'date_to'];

    const STATUS_SUBMITTED = 'Submitted';
    const STATUS_APPROVED_ADMIN = 'Approved_Admin';
    const STATUS_APPROVED_DATUK = 'Approved_Datuk';
    const STATUS_APPROVED_HR = 'Approved_HR';
    const STATUS_APPROVED_FINANCE = 'Approved_Finance';
    const STATUS_DONE = 'Done';

    protected $casts = [
        'submitted_at' => 'datetime',
        'date_from' => 'datetime',
        'date_to' => 'datetime',
    ];


    protected $fillable = [
        'user_id',
        'title',
        'description',
        'petrol_amount',
        'status',
        'claim_type',
        'submitted_at',
        'claim_company',
        'toll_amount',
        'from_location',
        'to_location',
        'date_from',
        'date_to',
        'total_distance_input',
    ];

    protected $primaryKey = 'id';
    protected $table = 'claim';

    public function locations()
    {
        return $this->belongsTo(ClaimLocation::class);
    }

    public function documents()
    {
        return $this->hasMany(ClaimDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
