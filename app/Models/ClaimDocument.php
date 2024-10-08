<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'toll_file_name',
        'toll_file_path',
        'email_file_name',
        'email_file_path',
        'uploaded_by',
    ];

    
}
