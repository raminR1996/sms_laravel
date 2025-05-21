@extends('layouts.layout')

@section('title', 'ویرایش کانتکت')

@section('content')
    <x-breadcrumb />
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>ویرایش کانتکت</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="village_id" class="form-label">انتخاب روستا</label>
                        <select name="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror">
                            <option value="">یک روستا انتخاب کنید</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->id }}" {{ $contact->village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                            @endforeach
                        </select>
                        @error('village_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="mobile_number" class="form-label">شماره موبایل</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" value="{{ $contact->mobile_number }}" placeholder="09123456789">
                            @error('mobile_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">به‌روزرسانی</button>
                </form>
            </div>
        </div>
    </div>
@endsection