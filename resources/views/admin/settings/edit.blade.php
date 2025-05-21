@extends('layouts.layout')

@section('title', 'ویرایش تنظیمات')
@section('css')
  <!-- فایل CSS -->
    <link rel="stylesheet" href="{{ asset('css/settings-page.css') }}">
@endsection
@section('content')

<!-- فراخوانی کامپوننت نان بری -->
<x-breadcrumb />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="settings-card">
                <h1 class="text-center">ویرایش تنظیمات</h1>

                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.settings.update', $setting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="key" class="form-label">کلید</label>
                        <input type="text" name="key" id="key"
                               class="form-control @error('key') is-invalid @enderror"
                               value="{{ old('key', $setting->key) }}" required>
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">مقدار</label>
                        <textarea name="value" id="value" rows="4"
                                  class="form-control @error('value') is-invalid @enderror">{{ old('value', $setting->value) }}</textarea>
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