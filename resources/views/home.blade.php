@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">خوش آمدید به فروشگاه آنلاین</h1>
        </div>
    </div>
    <div class="row">
        <!-- نمونه کارت محصول -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="محصول">
                <div class="card-body">
                    <h5 class="card-title">محصول نمونه</h5>
                    <p class="card-text">توضیح کوتاه درباره محصول.</p>
                    <p class="card-text"><strong>قیمت: ۱۰۰,۰۰۰ تومان</strong></p>
                    <a href="#" class="btn btn-primary">افزودن به سبد خرید</a>
                </div>
            </div>
        </div>
        <!-- کارت‌های بیشتر می‌تونن اینجا اضافه بشن -->
    </div>
@endsection