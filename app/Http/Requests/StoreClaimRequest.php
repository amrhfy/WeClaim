<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }
    public function rules(): array
    {
        return [
            'claim_company' => 'required|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'remarks' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'toll_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'email_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
