@extends('layouts.layout')

@section('content')
    <div class="container my-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">پنل کاربری</h5>
                        <ul class="nav flex-column dashboard-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#orders" data-bs-toggle="tab"><i class="fas fa-shopping-bag me-2"></i>خریدها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#payments" data-bs-toggle="tab"><i class="fas fa-credit-card me-2"></i>پرداخت‌ها</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#support" data-bs-toggle="tab"><i class="fas fa-headset me-2"></i>پشتیبانی</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#profile" data-bs-toggle="tab"><i class="fas fa-user me-2"></i>پروفایل</a>
                            </li>
                            <li class="nav-item">
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit" class="nav-link text-danger w-100 text-start"><i class="fas fa-sign-out-alt me-2"></i>خروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Orders Tab -->
                    <div class="tab-pane fade show active" id="orders">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">خریدها</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>شماره سفارش</th>
                                            <th>تاریخ</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>1404/02/15</td>
                                            <td>500,000 تومان</td>
                                            <td><span class="badge bg-success">تحویل شده</span></td>
                                        </tr>
                                        <!-- نمونه‌های بیشتر بعداً از دیتابیس لود می‌شن -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payments Tab -->
                    <div class="tab-pane fade" id="payments">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">پرداخت‌ها</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>شماره تراکنش</th>
                                            <th>تاریخ</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#TX67890</td>
                                            <td>1404/02/15</td>
                                            <td>500,000 تومان</td>
                                            <td><span class="badge bg-success">موفق</span></td>
                                        </tr>
                                        <!-- نمونه‌های بیشتر بعداً از دیتابیس لود می‌شن -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Support Tab -->
                    <div class="tab-pane fade" id="support">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">پشتیبانی</h4>
                                <form action="/support" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">موضوع</label>
                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">پیام</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">ارسال تیکت</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div class="tab-pane fade" id="profile">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-4">پروفایل</h4>
                                <form action="/profile" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">نام کامل</label>
                                        <input type="text" class="form-control" id="name" name="name" value="نام کاربر" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">ایمیل</label>
                                        <input type="email" class="form-control" id="email" name="email" value="user@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">شماره تماس</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="09123456789">
                                    </div>
                                    <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection