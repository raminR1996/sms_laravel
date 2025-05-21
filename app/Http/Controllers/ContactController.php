<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function showForm()
    {
        $villages = Village::all();
        return view('contacts.form', compact('villages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'village_id' => 'required|exists:villages,id',
            'mobile_number' => 'required|regex:/^09[0-9]{9}$/',
            'full_name' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
        ]);

        Contact::create([
            'village_id' => $request->village_id,
            'mobile_number' => $request->mobile_number,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        return redirect()->back()->with('success', 'اطلاعات با موفقیت ثبت شد.');
    }
}