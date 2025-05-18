@extends('layouts.layout')

@section('title', 'افزودن تنظیمات جدید')

@section('content')
<style>
    .settings-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        padding: 2rem;
        margin-top: 2rem;
    }

    .settings-card h1 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        font-weight: bold;
        color: #343a40;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-primary {
        width: 100%;
        padding: 0.6rem;
        font-weight: bold;
    }
</style>
<!-- فراخوانی کامپوننت نان بری -->
<x-breadcrumb />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="settings-card">
                <h1 class="text-center">افزودن تنظیمات جدید</h1>

                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.settings.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="key" class="form-label">کلید</label>
                        <input type="text" name="key" id="key"
                               class="form-control @error('key') is-invalid @enderror"
                               value="{{ old('key') }}" required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">مقدار</label>
                        <textarea name="value" id="value" rows="4"
                                  class="form-control @error('value') is-invalid @enderror">{{ old('value') }}</textarea>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">ذخیره تنظیمات</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection