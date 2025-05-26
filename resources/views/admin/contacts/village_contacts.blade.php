@extends('layouts.layout')

@section('title', 'شماره‌های روستا')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endsection

@section('content')
    <x-breadcrumb />
    <div class="container">
        <div class="settings-card">
            <div class="settings-card-header">
                <h1>شماره‌های روستا: {{ $village->name }}</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div id="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">در حال بارگذاری...</span>
                </div>
            </div>

            <div id="data-table-container">
                <div class="table-responsive">
                    <table id="contactsTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>شماره موبایل</th>
                                <th>نام کامل</th>
                                <th>جنسیت</th>
                                <th>تاریخ تولد</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary mt-3">بازگشت به لیست روستاها</a>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#loading-spinner').hide();
                $('#data-table-container').fadeIn();
            }, 1000);

            $('#contactsTable').DataTable({
                processing: true,
                serverSide: true,
                  responsive: true,
        sScrollX: "100%",
        sScrollXInner: "110%",
        bScrollCollapse: true,
                ajax: "{{ route('admin.contacts.village.data', $village->id) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'mobile_number', name: 'mobile_number'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'birth_date', name: 'birth_date'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                                            language: {
    url: '{{ asset("i18n/fa.json") }}'
},
  
                pageLength: 10,
                lengthChange: false,
                autoWidth: false
            });
        });
    </script>
@endsection
