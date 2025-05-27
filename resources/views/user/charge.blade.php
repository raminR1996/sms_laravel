@extends('layouts.layout')

@section('title',  ' شارژ پنل')

@section('content')
    <div class="dashboard-page">
        <div class="container">
            <h1 class="text-center mb-4">شارژ پنل</h1>

            <!-- لیست بسته‌ها -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">انتخاب بسته شارژ</h5>
                    <p class="text-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        هشدار: هر پیامک برابر با 70 کاراکتر است.
                    </p>
                    <div class="row">
                        @forelse ($packages as $package)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>{{ $package->sms_count }} پیامک</h6>
                                        <p>{{ number_format($package->price) }} تومان</p>
                                        <form action="{{ route('payment.purchase') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <button type="submit" class="btn btn-primary">خرید</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center">هیچ بسته‌ای در دسترس نیست.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- لیست پرداخت‌های موفق -->
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

            <!-- لیست بسته‌های خریداری‌شده -->
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
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('form button[type="submit"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'هشدار',
                    text: 'هر پیامک برابر با 70 کاراکتر است. آیا ادامه می‌دهید؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله',
                    cancelButtonText: 'خیر',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.submit();
                    }
                });
            });
        });
    </script>
@endsection