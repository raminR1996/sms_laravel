@extends('layouts.layout')

@section('title', 'بسته‌های خریداری‌شده')

@section('content')
    <div class="dashboard-page">
        <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">بسته‌های خریداری‌شده</h1>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">بسته‌های خریداری‌شده</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>تعداد پیامک</th>
                                <th>مبلغ (تومان)</th>
                                <th>تاریخ خرید</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($userPackages as $userPackage)
                                <tr>
                                    <td>{{ $userPackage->sms_count }}</td>
                                    <td>{{ number_format($userPackage->price) }}</td>
                                    <td>{{ $userPackage->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">هیچ بسته‌ای خریداری نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection