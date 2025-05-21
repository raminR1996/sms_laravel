@extends('layouts.layout')

@section('title', 'مدیریت کاربران')
@section('css')
  <!-- فایل CSS -->
    <link rel="stylesheet" href="{{ asset('css/users-page.css') }}">
@endsection
@section('content')
    <div class="users-page">
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h1>مدیریت کاربران</h1>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> بازگشت به داشبورد
                            </a>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success text-center">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger text-center">{{ session('error') }}</div>
                        @endif

                        <!-- فرم جستجو -->
                        <form action="{{ route('admin.users.index') }}" method="GET" class="search-form">
                            <input type="text" name="search" placeholder="جستجو بر اساس نام، ایمیل، شماره یا نقش..." value="{{ request('search') }}">
                            <button type="submit"><i class="fas fa-search"></i> جستجو</button>
                        </form>

                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> افزودن کاربر جدید
                        </a>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>نام</th>
                                        <th>ایمیل</th>
                                        <th>شماره تماس</th>
                                        <th>نقش</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->name ?? 'نامشخص' }}</td>
                                            <td>{{ $user->email ?? 'نامشخص' }}</td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>{{ $user->role }}</td>
       <td class="action-buttons">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-warning" title="ویرایش کاربر">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-danger" title="حذف کاربر" onclick="return confirm('آیا مطمئن هستید؟')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">هیچ کاربری یافت نشد.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- صفحه‌بندی -->
                        <div class="pagination">
                            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection