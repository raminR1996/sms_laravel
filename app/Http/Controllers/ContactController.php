<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;



class ContactController extends Controller
{
    public function showForm()
    {
        $villages = Village::all();
        return view('contacts.form', compact('villages'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'village_id' => 'required|exists:villages,id',
                'mobile_number' => 'required|regex:/^09[0-9]{9}$/|unique:contacts,mobile_number',
                'full_name' => 'nullable|string|max:255',
                'gender' => 'required|in:male,female',
                'birth_date' => 'nullable|date|before:today',
            ]);

            Contact::create([
                'village_id' => $request->village_id,
                'mobile_number' => $request->mobile_number,
                'full_name' => $request->full_name,
                'gender' => $request->gender ?? 'male',
                'birth_date' => $request->birth_date,
            ]);

            return redirect()->back()->with('success', 'اطلاعات با موفقیت ثبت شد.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'Column \'gender\' cannot be null')) {
                    return redirect()->back()->withErrors(['gender' => 'فیلد جنسیت نمی‌تواند خالی باشد.'])->withInput();
                }
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    return redirect()->back()->withErrors(['mobile_number' => 'این شماره موبایل قبلاً ثبت شده است.'])->withInput();
                }
            }
            return redirect()->back()->withErrors(['general' => 'خطایی در ثبت اطلاعات رخ داد. لطفاً دوباره تلاش کنید.'])->withInput();
        }
    }
}
