<?php

namespace App\Http\Controllers;

use App\Models\ClaimDocument;
use Illuminate\Http\Request;

class TollReport extends Controller
{
    //

    public function store(Request $request) {
        
        $request->validate([
            'toll_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('toll_report')) {
            $file = $request->file('toll_report');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/claims', $filename, 'public');


            ClaimDocument::create([
                'claim_id' => $request->input('claim_id'),
            ]);
        }

        return redirect()->route('home')->with('success', 'Toll report uploaded successfully.');


    }


}
