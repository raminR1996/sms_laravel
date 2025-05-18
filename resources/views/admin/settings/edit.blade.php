@extends('layouts.layout')

@section('title', $settings->site_title . ' - تنظیمات')

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

    .alert {
        margin-top: 1rem;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="settings-card">

                <h1 class="text-center">ویرایش تنظیمات سایت</h1>

                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="site_title" class="form-label">عنوان سایت</label>
                        <input type="text" name="site_title" id="site_title"
                               class="form-control @error('site_title') is-invalid @enderror"
                               value="{{ old('site_title', $settings->site_title ?? '') }}" required>
                        @error('site_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">توضیحات سایت</label>
                        <textarea name="site_description" id="site_description" rows="4"
                                  class="form-control @error('site_description') is-invalid @enderror">{{ old('site_description', $settings->site_description ?? '') }}</textarea>
                        @error('site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="default_sms_number" class="form-label">شماره پیش‌فرض پیامک</label>
                        <input type="text" name="default_sms_number" id="default_sms_number"
                               class="form-control @error('default_sms_number') is-invalid @enderror"
                               value="{{ old('default_sms_number', $settings->default_sms_number ?? '') }}">
                        @error('default_sms_number')
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
