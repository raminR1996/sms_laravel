@extends('layouts.layout')

@section('title', 'افزودن تنظیمات جدید')
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