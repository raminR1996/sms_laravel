@extends('layouts.layout')

@section('title','  مدیریت خطوط')
@section('content')
    <div class="dashboard-page">
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">مدیریت خطوط</h1>
            <div class="mb-4">
                <a href="{{ route('admin.lines.create') }}" class="btn btn-primary">افزودن خط جدید</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>شماره خط</th>
                                <th>نام اپراتور</th>
                                <th>نوع خط</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lines as $line)
                                <tr>
                                    <td>{{ $line->line_number }}</td>
                                    <td>{{ $line->operator_name }}</td>
                                    <td>{{ $line->line_type == 'advertising' ? 'تبلیغاتی' : 'خدماتی' }}</td>
                                    <td>
                                        <form action="{{ route('admin.lines.toggle', $line) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $line->is_active ? 'success' : 'secondary' }} btn-sm">
                                                {{ $line->is_active ? 'فعال' : 'غیرفعال' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.lines.edit', $line) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> ویرایش
                                        </a>
                                        <form action="{{ route('admin.lines.destroy', $line) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('آیا مطمئن هستید که می‌خواهید این خط را حذف کنید؟')">
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