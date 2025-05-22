
@extends('layouts.layout')

@section('title', 'آپلود مدارک')
@section('content')
    <div class="container">
        <h1 class="text-center mb-4">آپلود مدارک</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="national_id_photo" class="form-label">عکس کارت ملی</label>
                <input type="file" name="national_id_photo" id="national_id_photo" class="form-control" required>
                @error('national_id_photo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="selfie_with_id_photo" class="form-label">سلفی با کارت ملی</label>
                <input type="file" name="selfie_with_id_photo" id="selfie_with_id_photo" class="form-control" required>
                @error('selfie_with_id_photo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">ارسال مدارک</button>
        </form>
    </div>
@endsection
