<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class DocumentController extends Controller
{
     public function verifyDocuments()
    {
        $documents = Document::with('user')->where('verified', false)->get();
        return view('admin.document.verify_documents', compact('documents'));
    }

    public function approveDocument(Request $request, Document $document)
    {
        $document->update(['verified' => true]);
        $document->user->update(['documents_verified' => true]);

        return redirect()->back()->with('success', 'مدارک کاربر تأیید شد.');
    }

       public function rejectDocument(Request $request, Document $document)
    {
        // حذف فایل‌ها اگر وجود داشته باشن
        if ($document->national_id_photo) {
            Storage::disk('public')->delete($document->national_id_photo);
        }
        if ($document->selfie_with_id_photo) {
            Storage::disk('public')->delete($document->selfie_with_id_photo);
        }

        // حذف کامل رکورد از جدول documents
        $document->delete();

        // به‌روزرسانی وضعیت تأیید مدارک در جدول users
        $document->user->update(['documents_verified' => false]);

        return redirect()->back()->with('success', 'مدارک رد شد. کاربر می‌تواند مدارک جدیدی آپلود کند.');
    }
}
