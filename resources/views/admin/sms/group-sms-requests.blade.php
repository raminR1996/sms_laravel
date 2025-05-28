@extends('layouts.layout')

@section('title', 'مدیریت درخواست‌های پیامک گروهی')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">مدیریت درخواست‌های پیامک گروهی</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>خط</th>
                        <th>روستاها</th>
                        <th>پیام</th>
                        <th>تعداد پیامک</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->line_number }}</td>
                            <td>
                                @php
                                    $villages = \App\Models\Village::whereIn('id', $request->village_ids)->pluck('name')->toArray();
                                @endphp
                                {{ implode(', ', $villages) }}
                            </td>
                            <td>{{ Str::limit($request->message, 50) }}</td>
                            <td>{{ $request->sms_count }}</td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning">در انتظار</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success">تأیید شده</span>
                                @else
                                    <span class="badge bg-danger">رد شده</span>
                                @endif
                            </td>
                            <td>
                                @if($request->status === 'pending')
                                    <form action="{{ route('admin.group-sms.approve', $request) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-success btn-sm">تأیید</button>
                                    </form>
                                    <form action="{{ route('admin.group-sms.approve', $request) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-sm">رد</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection