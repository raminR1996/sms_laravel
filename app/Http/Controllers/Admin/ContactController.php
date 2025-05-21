<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ]);

        Contact::create([
            'village_id' => $request->village_id,
            'mobile_number' => $request->mobile_number,
        ]);

        return redirect()->back()->with('success', 'اطلاعات با موفقیت ثبت شد.');
    }

    public function index()
    {
        $contacts = Contact::with('village')->get();
        return view('admin.contacts.index', compact('contacts'));
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $villages = Village::all();
        return view('admin.contacts.edit', compact('contact', 'villages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'village_id' => 'required|exists:villages,id',
            'mobile_number' => 'required|regex:/^09[0-9]{9}$/',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update([
            'village_id' => $request->village_id,
            'mobile_number' => $request->mobile_number,
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'اطلاعات با موفقیت به‌روزرسانی شد.');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'رکورد با موفقیت حذف شد.');
    }
}