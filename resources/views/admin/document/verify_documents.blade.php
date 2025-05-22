@extends('layouts.layout')

@section('title', isset($siteTitle) ? $siteTitle . ' - تأیید مدارک کاربران' : 'پنل مدیریت - تأیید مدارک کاربران')
@section('content')
    <div class="container">
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <h1 class="text-center mb-4">تأیید مدارک کاربران</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($documents->isEmpty())
            <p class="text-center">هیچ مدرکی برای تأیید وجود ندارد.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>عکس کارت ملی</th>
                        <th>سلفی با کارت ملی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->user->name }}</td>
                            <td>
                                @if ($document->national_id_photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#documentModal_{{ $document->id }}_national">
                                        <img src="{{ Storage::url($document->national_id_photo) }}" alt="کارت ملی" style="max-width: 100px; cursor: pointer;" class="img-thumbnail">
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="documentModal_{{ $document->id }}_national" tabindex="-1" aria-labelledby="documentModalLabel_{{ $document->id }}_national" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="documentModalLabel_{{ $document->id }}_national">عکس کارت ملی</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ Storage::url($document->national_id_photo) }}" alt="کارت ملی" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    بدون تصویر
                                @endif
                            </td>
                            <td>
                                @if ($document->selfie_with_id_photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#documentModal_{{ $document->id }}_selfie">
                                        <img src="{{ Storage::url($document->selfie_with_id_photo) }}" alt="سلفی" style="max-width: 100px; cursor: pointer;" class="img-thumbnail">
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="documentModal_{{ $document->id }}_selfie" tabindex="-1" aria-labelledby="documentModalLabel_{{ $document->id }}_selfie" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="documentModalLabel_{{ $document->id }}_selfie">سلفی با کارت ملی</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ Storage::url($document->selfie_with_id_photo) }}" alt="سلفی" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    بدون تصویر
                                @endif
                            </td>
                            <td>
                                @if ($document->national_id_photo || $document->selfie_with_id_photo)
                                    <form action="{{ route('admin.documents.approve', $document) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">تأیید</button>
                                    </form>
                                    <form action="{{ route('admin.documents.reject', $document) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">رد</button>
                                    </form>
                                @else
                                    <span class="text-muted">مدارک حذف شده‌اند</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @section('js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // دیباگ برای اطمینان از لود شدن Bootstrap
                if (typeof bootstrap === 'undefined') {
                    console.error('Bootstrap JS is not loaded!');
                } else {
                    console.log('Bootstrap JS loaded successfully.');

                    // اضافه کردن event listener برای modal
                    var modals = document.querySelectorAll('.modal');
                    modals.forEach(function (modal) {
                        modal.addEventListener('show.bs.modal', function () {
                            console.log('Modal with ID ' + modal.id + ' is opening');
                        });
                    });
                }
            });
        </script>
    @endsection
@endsection
