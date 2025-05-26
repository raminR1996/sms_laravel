@extends('layouts.layout')

@section('title',  ' مدیریت بسته‌های شارژ')

@section('content')
    <div class="dashboard-page">
                    <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">مدیریت بسته‌های شارژ</h1>
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">ایجاد بسته جدید</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>تعداد پیامک</th>
                                <th>قیمت (تومان)</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $package)
                                <tr>
                                    <td>{{ $package->sms_count }}</td>
                                    <td>{{ number_format($package->price) }}</td>
                                    <td>
                                        <form action="{{ route('admin.packages.toggle', $package) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn {{ $package->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                {{ $package->is_active ? 'فعال' : 'غیرفعال' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning btn-icon">
                                            <i class="fas fa-edit"></i> ویرایش
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-icon" onclick="return confirm('آیا مطمئن هستید؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection