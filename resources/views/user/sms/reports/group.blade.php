@extends('layouts.layout')

@section('title', 'گزارشات پیامک گروهی')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
<style>
    .modal {
        z-index: 1055 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .modal-dialog {
        position: relative;
        pointer-events: auto;
    }
    .modal-content {
        pointer-events: auto;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin: 10px 0;
    }
    .dataTables_wrapper .dataTables_filter input {
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center mb-4">گزارشات پیامک گروهی</h1>
    <div class="card">
        <div class="card-body">
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

<!-- مودال عمومی برای نمایش شماره‌ها و وضعیت‌ها -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">شماره‌ها و وضعیت‌ها</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="statusModalList">
                    <!-- محتوای شماره‌ها و وضعیت‌ها با جاوااسکریپت پر می‌شود -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
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
        // تنظیم Datatables
        setTimeout(function () {
            document.getElementById('loading-spinner').style.display = 'none';
            document.getElementById('data-table-container').style.display = 'block';

            $('#groupReportsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                sScrollX: "100%",
                sScrollXInner: "110%",
                bScrollCollapse: true,
                ajax: '{{ route("group.reports.data") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'line_number', name: 'line_number' },
                    { data: 'villages', name: 'villages', orderable: false, searchable: true },
                    { data: 'numbers_count', name: 'numbers_count', orderable: false, searchable: false },
                    { data: 'message', name: 'message' },
                    { data: 'sms_count', name: 'sms_count' },
                    { data: 'status', name: 'status', orderable: false, searchable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '{{ asset("i18n/fa.json") }}'
                },
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
            });
        }, 1000);

        // مدیریت باز کردن مودال
        const modalElement = document.querySelector('#statusModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalList = document.querySelector('#statusModalList');

        $(document).on('click', '.show-status-modal', function (event) {
            event.preventDefault();
            event.stopPropagation();

            let numbers, statuses, datetimes;

            try {
                numbers = JSON.parse(this.getAttribute('data-numbers') || '[]');
                statuses = JSON.parse(this.getAttribute('data-statuses') || '[]');
                datetimes = JSON.parse(this.getAttribute('data-datetimes') || '[]');
            } catch (e) {
                console.error('Error parsing JSON data:', e);
                modalList.innerHTML = '<p class="text-danger">خطا در بارگذاری داده‌ها. لطفاً دوباره تلاش کنید.</p>';
                modal.show();
                return;
            }

            modalList.innerHTML = '';

            if (!Array.isArray(numbers) || numbers.length === 0) {
                modalList.innerHTML = '<p class="text-danger">شماره‌ها نامعتبر یا خالی هستند.</p>';
                modal.show();
                return;
            }

            numbers.forEach((number, index) => {
                const status = (Array.isArray(statuses) && statuses[index]) ? statuses[index] : 'unknown';
                const datetime = (Array.isArray(datetimes) && datetimes[index]) ? datetimes[index] : 'نامشخص';
                let badgeClass = 'bg-secondary';
                let statusText = 'وضعیت دریافت نشده';

                switch (status) {
                    case 'DELIVERED':
                        badgeClass = 'bg-success';
                        statusText = 'رسیده';
                        break;
                    case 'ENQUEUED':
                        badgeClass = 'bg-warning';
                        statusText = 'در انتظار';
                        break;
                    case 'FAILED':
                        badgeClass = 'bg-danger';
                        statusText = 'نرسیده';
                        break;
                    default:
                        badgeClass = 'bg-secondary';
                        statusText = 'نامشخص';
                }

                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <div>
                        <strong>${number}</strong>
                        <small class="d-block text-muted">تاریخ: ${datetime}</small>
                    </div>
                    <span class="badge ${badgeClass}">${statusText}</span>
                `;
                modalList.appendChild(listItem);
            });

            modal.show();
        });

        modalElement.addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
        });
    });
</script>
@endsection