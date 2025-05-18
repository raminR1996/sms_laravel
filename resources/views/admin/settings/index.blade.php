@extends('layouts.layout')

@section('title', 'تنظیمات سایت')

@section('content')
<!-- اضافه کردن Font Awesome برای آیکون‌ها -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .settings-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-top: 2rem;
        transition: all 0.3s ease;
    }

    .settings-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .settings-card-header {
        background: #007bff;
        color: #ffffff;
        padding: 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
        margin: -2rem -2rem 2rem -2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .settings-card h1 {
        font-size: 2rem;
        margin: 0;
        font-weight: 700;
        text-align: center;
    }

    .breadcrumb {
        background: #f8f9fa;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #0056b3;
    }

    .table-responsive {
        margin-top: 1.5rem;
    }

    .table {
        border-radius: 0.5rem;
        overflow: hidden;
        background: #f8f9fa;
    }

    .table thead th {
        background: #007bff;
        color: #ffffff;
        border-bottom: none;
        font-weight: 600;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #e9ecef;
    }

    .table td, .table th {
        vertical-align: middle;
        padding: 1rem;
    }

    .btn-primary {
        background: #007bff;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .btn-warning {
        background: #ffc107;
        border: none;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background: #e0a800;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #dc3545;
        border: none;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        padding: 0.75rem 1.5rem;
        color: #ffffff;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .alert-success {
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<!-- فراخوانی کامپوننت نان بری -->
<x-breadcrumb />

<div class="container">
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
                                    <td>
                                        <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> ویرایش
                                        </a>
                                        <form action="{{ route('admin.settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('آیا مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i> حذف
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
@endsection