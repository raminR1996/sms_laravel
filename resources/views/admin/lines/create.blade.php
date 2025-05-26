@extends('layouts.layout')

@section('title',  '  افزودن خط جدید')
@section('content')
    <div class="dashboard-page">
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">افزودن خط جدید</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.lines.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="line_number" class="form-label">شماره خط</label>
                            <input type="text" class="form-control" id="line_number" name="line_number" required>
                            @error('line_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="operator_name" class="form-label">نام اپراتور</label>
                            <input type="text" class="form-control" id="operator_name" name="operator_name" required>
                            @error('operator_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="line_type" class="form-label">نوع خط</label>
                            <select class="form-control" id="line_type" name="line_type" required>
                                <option value="advertising">تبلیغاتی</option>
                                <option value="service">خدماتی</option>
                            </select>
                            @error('line_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">وضعیت</label>
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value="1">فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                            @error('is_active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">ذخیره</button>
                        <a href="{{ route('admin.lines.index') }}" class="btn btn-outline-secondary">لغو</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection