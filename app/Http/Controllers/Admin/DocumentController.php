<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        // حذف فایل‌ها اگر وجود داشته باشند
        if ($document->national_id_photo && Storage::exists('public/' . $document->national_id_photo)) {
            Storage::delete('public/' . $document->national_id_photo);
        }
        if ($document->selfie_with_id_photo && Storage::exists('public/' . $document->selfie_with_id_photo)) {
            Storage::delete('public/' . $document->selfie_with_id_photo);
        }

        // حذف رکورد از دیتابیس
        $document->delete();

        // به‌روزرسانی وضعیت کاربر
        $document->user->update(['documents_verified' => false]);

        return redirect()->back()->with('success', 'مدارک رد شد. کاربر می‌تواند مدارک جدیدی آپلود کند.');
    }

    public function serveDocument($filename): StreamedResponse
    {
        $path = 'public/documents/' . $filename;

        // بررسی وجود فایل
        if (!Storage::exists($path)) {
            abort(404, 'تصویر یافت نشد.');
        }

        // استریم فایل
        return Storage::response($path);
    }
}