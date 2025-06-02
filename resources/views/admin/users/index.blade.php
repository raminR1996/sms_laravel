@extends('layouts.layout')

@section('title', 'مدیریت کاربران')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
    <div class="users-page">
        <x-breadcrumb />
        <div class="container">
            <div class="row justify-content-center">
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <h1>مدیریت کاربران</h1>
                           
                        </div>

                 

                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> افزودن کاربر جدید
                        </a>

                        <!-- لودینگ اسپینر -->
                        <div id="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">در حال بارگذاری...</span>
                            </div>
                        </div>

                        <!-- جدول -->
                        <div id="data-table-container" style="display: none;">
                            <div class="table-responsive">
                                <table id="usersTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام</th>
                                            <th>ایمیل</th>
                                            <th>شماره تماس</th>
                                            <th>نقش</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
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

                $('#usersTable').DataTable({
                 processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('admin.users.data') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'phone_number', name: 'phone_number' },
                        { data: 'role', name: 'role' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    language: {
                        url: '{{ asset("i18n/fa.json") }}'
                    },
                    paging: true,
                    pageLength: 10,
                    lengthChange: false,
                    searching: true, // فعال کردن جستجوی داخلی DataTables                    ordering: true,
                    info: true,
                    autoWidth: false,
                });
            }, 1000); // 1 ثانیه دیلی
        });
    </script>
@endsection