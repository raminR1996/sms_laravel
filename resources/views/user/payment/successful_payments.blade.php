@extends('layouts.layout')

@section('title', 'پرداخت‌های موفق')

@section('content')
    <div class="dashboard-page">
        <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">پرداخت‌های موفق</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">پرداخت‌های موفق</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>مبلغ (تومان)</th>
                                <th>شناسه تراکنش</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td>{{ number_format($payment->amount) }}</td>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">هیچ پرداختی ثبت نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection