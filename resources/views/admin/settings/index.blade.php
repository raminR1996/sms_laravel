@extends('layouts.layout')

@section('title', 'تنظیمات سایت')

@section('css')
  <!-- فایل CSS -->
    <link rel="stylesheet" href="{{ asset('css/settings-page.css') }}">
@endsection
@section('content')
<!-- اضافه کردن Font Awesome برای آیکون‌ها -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- اضافه کردن کلاس settings-page به بدنه یا یک دیو -->
<div class="settings-page">
    <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />

    <div class="container settings-container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h1>مدیریت تنظیمات سایت</h1>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> بازگشت به داشبورد
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <a href="{{ route('admin.settings.create') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-plus"></i> افزودن تنظیمات جدید
                    </a>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>کلید</th>
                                    <th>مقدار</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                    <tr>
                                        <td>{{ $setting->key }}</td>
                                        <td>{{ Str::limit($setting->value, 50) }}</td>
                                     <td class="action-buttons">
    <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-icon btn-warning" title="ویرایش تنظیم">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-danger" title="حذف تنظیم" onclick="return confirm('آیا مطمئن هستید؟')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">هیچ تنظیماتی یافت نشد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection