@extends('layouts.layout')

@section('title', 'مدیریت کانتکت‌ها')

@section('content')
    <x-breadcrumb />
    <div class="container">
        <div class="settings-card">
            <div class="settings-card-header">
                <h1>مدیریت کانتکت‌ها</h1>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>روستا</th>
                            <th>شماره موبایل</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->village->name }}</td>
                                <td>{{ $contact->mobile_number }}</td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.contacts.edit', $contact->id) }}" class="btn btn-warning btn-icon" title="ویرایش کانتکت">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-icon" title="حذف کانتکت" onclick="return confirm('آیا مطمئن هستید؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection