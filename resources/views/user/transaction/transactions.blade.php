@extends('layouts.layout')

@section('title', 'تراکنش‌ها')

@section('content')
    <div class="dashboard-page">
        <x-breadcrumb />
        <div class="container">
            <h1 class="text-center mb-4">تراکنش‌ها</h1>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">تراکنش‌های شارژ</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع</th>
                                <th>تعداد پیامک</th>
                                <th>مبلغ (تومان)</th>
                                <th>توضیحات</th>
                                <th>مانده شارژ</th>
                                <th>تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->type == 'credit' ? 'افزایش شارژ' : 'کاهش شارژ' }}</td>
                                    <td>{{ $transaction->sms_count }}</td>
                                    <td>{{ $transaction->amount ? number_format($transaction->amount) : '-' }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ $transaction->sms_balance_after }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">هیچ تراکنشی ثبت نشده است.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection