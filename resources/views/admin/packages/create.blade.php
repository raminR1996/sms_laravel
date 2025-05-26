@extends('layouts.layout')

@section('title', '  ایجاد بسته شارژ')

@section('content')
    <div class="dashboard-page">
                    <!-- فراخوانی کامپوننت نان بری -->
    <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">ایجاد بسته شارژ</h1>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.packages.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="sms_count">تعداد پیامک</label>
                            <select name="sms_count" id="sms_count" class="form-control" required>
                                <option value="100">100 پیامک</option>
                                <option value="500">500 پیامک</option>
                                <option value="1000">1000 پیامک</option>
                                <option value="5000">5000 پیامک</option>
                                <option value="10000">10000 پیامک</option>
                                <option value="50000">50000 پیامک</option>
                            </select>
                            @error('sms_count')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="is_active">وضعیت</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1">فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">ایجاد بسته</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection