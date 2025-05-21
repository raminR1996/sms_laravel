@extends('layouts.public')

@section('css')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        /* General Card Styling */
        .card {
            background: #fff;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }
        .card-header.bg-gradient-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
            border-bottom: none;
        }
        .card-body {
            padding: 2rem !important;
        }

        /* Form Elements Styling */
        .form-label {
            color: #333;
            font-size: 1rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
            outline: none;
        }
        .input-group-text {
            background: #f8f9fa;
            border-radius: 10px 0 0 10px;
            border: 1px solid #ced4da;
        }

        /* Select2 Styling */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px !important;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            height: auto !important;
        }
        .select2-container--bootstrap-5 .select2-selection__rendered {
            line-height: 1.5 !important;
        }
        .select2-container--bootstrap-5 .select2-selection__arrow {
            height: 100% !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single {
            padding: 0.75rem !important;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(90deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0056b3, #003d82);
        }

        /* Alert Styling */
        .alert-success {
            border-radius: 10px;
            background: #d4edda;
            color: #155724;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem !important;
            }
            .form-label {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-gradient-primary text-white text-center rounded-top py-3">
                        <h4 class="mb-0">ثبت اطلاعات تماس</h4>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="village_id" class="form-label fw-bold">انتخاب روستا</label>
                                <select name="village_id" id="village_id" class="form-select select2 @error('village_id') is-invalid @enderror">
                                    <option value="">یک روستا انتخاب کنید</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                                @error('village_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="mobile_number" class="form-label fw-bold">شماره موبایل</label>
                                <div class="input-group">
                                    
                                    <input type="text" name="mobile_number" id="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror" placeholder="09123456789" value="{{ old('mobile_number') }}">
                                    @error('mobile_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="full_name" class="form-label fw-bold">نام کامل (اختیاری)</label>
                                <input type="text" name="full_name" id="full_name" class="form-control @error('full_name') is-invalid @enderror" placeholder="نام و نام خانوادگی" value="{{ old('full_name') }}">
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="gender" class="form-label fw-bold">جنسیت (اختیاری)</label>
                                <select name="gender" id="gender" class="form-select select2 @error('gender') is-invalid @enderror">
                                    <option value="">انتخاب کنید</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>مرد</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>زن</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="birth_date" class="form-label fw-bold">تاریخ تولد (اختیاری)</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">ثبت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "یک گزینه انتخاب کنید",
                allowClear: true,
                dir: "rtl",
                theme: "bootstrap-5"
            });
        });
    </script>
@endsection