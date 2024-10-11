<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

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
        return $this->belongsTo(User::class, 'user_id');
    }
}
