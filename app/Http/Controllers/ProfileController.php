<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Document;
use App\Models\Documents;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function showCompleteForm()
    {
        return view('profile.complete');
    }

    public function storeCompleteForm(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_completed' => true, // علامت‌گذاری پروفایل به عنوان تکمیل‌شده
        ]);

        return redirect()->route('documents.upload.form')->with('success', 'پروفایل با موفقیت تکمیل شد. لطفاً مدارک خود را آپلود کنید.');
    }

    public function showDocumentsForm(): View
    {
        return view('profile.documents');
    }

    public function uploadDocuments(Request $request): RedirectResponse
    {
        $request->validate([
            'national_id_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'selfie_with_id_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        // آپلود فایل‌ها
        $nationalIdPath = $request->file('national_id_photo')->store('documents', 'public');
        $selfiePath = $request->file('selfie_with_id_photo')->store('documents', 'public');

        // ذخیره در جدول documents
        Document::updateOrCreate(
            ['user_id' => $user->id],
            [
                'national_id_photo' => $nationalIdPath,
                'selfie_with_id_photo' => $selfiePath,
                'verified' => false,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'مدارک با موفقیت آپلود شد. منتظر تأیید مدیریت باشید.');
    }
}
