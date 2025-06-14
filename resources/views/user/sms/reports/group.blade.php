@extends('layouts.layout')

@section('title', 'گزارشات پیامک گروهی')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
    <div class="reports-page">
         <x-breadcrumb />
        <div class="container">
            <div class="row justify-content-center">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h1>گزارشات پیامک گروهی</h1>
                    </div>

                    <!-- لودینگ اسپینر -->
                    <div id="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">در حال بارگذاری...</span>
                        </div>
                    </div>

                    <!-- جدول -->
                    <div id="data-table-container" style="display: none;">
                        <div class="table-responsive">
                            <table id="groupReportsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>تاریخ</th>
                                        <th>شماره خط</th>
                                        <th>روستاها</th>
                                        <th>تعداد شماره‌ها</th>
                                        <th>متن پیامک</th>
                                        <th>تعداد پیامک‌ها</th>
                                        <th>وضعیت</th>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.getElementById('loading-spinner').style.display = 'none';
                document.getElementById('data-table-container').style.display = 'block';

                $('#groupReportsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('group.reports.data') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'line_number', name: 'line_number' },
                        { data: 'villages', name: 'villages', orderable: false, searchable: true },
                        { data: 'numbers_count', name: 'numbers_count', orderable: false, searchable: false },
                        { data: 'message', name: 'message' },
                        { data: 'sms_count', name: 'sms_count' },
                        { data: 'status', name: 'status', orderable: false, searchable: true },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    language: {
                        url: '{{ asset("i18n/fa.json") }}'
                    },
                    paging: true,
                    pageLength: 10,
                    lengthChange: false,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                });
            }, 1000);
        });
    </script>
@endsection