@extends('layouts.layout')

@section('title', ' ویرایش خط')
@section('content')
    <div class="dashboard-page">
            <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">ویرایش خط</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.lines.update', $line) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="line_number" class="form-label">شماره خط</label>
                            <input type="text" class="form-control" id="line_number" name="line_number" value="{{ $line->line_number }}" required>
                            @error('line_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="operator_name" class="form-label">نام اپراتور</label>
                            <input type="text" class="form-control" id="operator_name" name="operator_name" value="{{ $line->operator_name }}" required>
                            @error('operator_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="line_type" class="form-label">نوع خط</label>
                            <select class="form-control" id="line_type" name="line_type" required>
                                <option value="advertising" {{ $line->line_type == 'advertising' ? 'selected' : '' }}>تبلیغاتی</option>
                                <option value="service" {{ $line->line_type == 'service' ? 'selected' : '' }}>خدماتی</option>
                            </select>
                            @error('line_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">وضعیت</label>
                            <select class="form-control" id="is_active" name="is_active" required>
                                <option value="1" {{ $line->is_active ? 'selected' : '' }}>فعال</option>
                                <option value="0" {{ !$line->is_active ? 'selected' : '' }}>غیرفعال</option>
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