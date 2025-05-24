<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

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
             return view('admin.contacts.index');
    }
    

public function getData()
{
    $villages = Village::withCount('contacts');

    return DataTables::of($villages)
        ->addIndexColumn()
        ->addColumn('actions', function($village) {
            // رندر blade به همراه متغیر
            return View::make('admin.contacts.actions', compact('village'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
}

public function showVillageContacts($village_id)
{
    $village = Village::findOrFail($village_id);
    return view('admin.contacts.village_contacts', compact('village'));
}

public function villageContactsData($id)
{
    $contacts = Contact::where('village_id', $id);

    return DataTables::of($contacts)
        ->addIndexColumn()
        ->editColumn('birth_date', function($contact) {
            return $contact->birth_date ? $contact->birth_date->format('Y-m-d') : '-';
        })
        ->editColumn('gender', function($contact) {
            return $contact->gender == 'male' ? 'مرد' : 'زن';
        })
        ->addColumn('actions', function($contact) {
            return view('admin.contacts.contact_actions', compact('contact'))->render();
        })
        ->rawColumns(['actions'])
        ->make(true);
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