@extends('layouts.layout')

@section('title', 'مدیریت کانتکت‌ها')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
    <x-breadcrumb />
    <div class="container">
        <div class="settings-card">
            <div class="settings-card-header">
                <h1>مدیریت روستاها و کانتکت‌ها</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- لودینگ اسپینر --}}
            <div id="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">در حال بارگذاری...</span>
                </div>
            </div>

            {{-- جدول --}}
            <div id="data-table-container">
                <div class="table-responsive">
                    <table id="villagesTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام روستا</th>
                                <th>تعداد شماره‌ها</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('data-table-container').style.display = 'block';

                $('#villagesTable').DataTable({
                    serverSide: true,
                    scrollX: true,
                    ajax: "{{ route('admin.contacts.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'name', name: 'name'},
                        {data: 'contacts_count', name: 'contacts_count'},
                        {data: 'actions', name: 'actions', orderable: false, searchable: false},
                    ],
                    language: {
                        emptyTable: "داده‌ای برای نمایش وجود ندارد",
                        info: "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                        infoEmpty: "نمایش 0 تا 0 از 0 رکورد",
                        infoFiltered: "(فیلتر شده از مجموع _MAX_ رکورد)",
                        lengthMenu: "نمایش _MENU_ رکورد",
                        loadingRecords: "در حال بارگذاری...",
                        processing: "در حال پردازش...",
                        search: "جستجو:",
                        zeroRecords: "رکوردی یافت نشد",
                        paginate: {
                            first: "اولین",
                            last: "آخرین",
                            next: "بعدی",
                            previous: "قبلی"
                        },
                        aria: {
                            sortAscending: ": فعال‌سازی مرتب‌سازی صعودی",
                            sortDescending: ": فعال‌سازی مرتب‌سازی نزولی"
                        }
                    },
                    paging: true,
                    pageLength: 10,
                    lengthChange: false,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                });
            }, 1000); // 1 ثانیه دیلی
        });
    </script>
@endsection
