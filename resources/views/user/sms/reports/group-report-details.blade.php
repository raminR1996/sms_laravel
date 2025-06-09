@extends('layouts.layout')

@section('title', 'جزئیات گزارش پیامک گروهی')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
    <div class="reports-details-page">
        <x-breadcrumb />
        <div class="container">
            <div class="row justify-content-center">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h1>جزئیات گزارش پیامک گروهی</h1>
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
                            <table id="statusDetailsTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>شماره</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
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

                $('#statusDetailsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('group.reports.details.data', $report->id) }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'number', name: 'number' },
                        { data: 'status', name: 'status' },
                        { data: 'datetime', name: 'datetime' },
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

                // به‌روزرسانی خودکار وضعیت هنگام بارگذاری صفحه
                $.ajax({
                    url: "{{ route('group.reports.update.status', $report->id) }}",
                    method: 'GET',
                    success: function (response) {
                        if (response.success) {
                            $('#statusDetailsTable').DataTable().ajax.reload();
                        }
                    },
                    error: function () {
                        console.error('Error updating status on page load');
                    }
                });
            }, 1000);
        });
    </script>
@endsection