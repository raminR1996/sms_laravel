@extends('layouts.layout')

@section('title', 'ارسال پیامک تکی')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
<div class="container">
    <h1 class="text-center mb-4">ارسال پیامک تکی</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('send.sms.single.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="line_number" class="form-label">شماره خط ارسالی:</label>
                    <select name="line_number" id="line_number" class="form-control select2" required>
                        <option value="" disabled selected>یک خط انتخاب کنید</option>
                        @foreach(\App\Models\Line::where('is_active', true)->get() as $line)
                            <option value="{{ $line->line_number }}">{{ $line->line_number }} ({{ $line->operator_name }} - {{ $line->line_type === 'service' ? 'خدماتی' : 'تبلیغاتی' }})</option>
                        @endforeach
                    </select>
                    @error('line_number')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="numbers" class="form-label">شماره‌ها (چندین شماره را با Enter جدا کنید):</label>
                    <select name="numbers[]" id="numbers" class="form-control select2" multiple="multiple" required>
                    </select>
                    @error('numbers')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

               <div class="mb-3">
    <label for="message" class="form-label">متن پیامک:</label>
    <textarea name="message" id="message" class="form-control" rows="5" required oninput="calculateSmsCount()"></textarea>
    @error('message')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
    <div class="sms-info mt-2">
        <span>تعداد کاراکتر: <span id="char-count">0</span> / <span id="max-chars">70</span></span> |
        <span>تعداد پیامک: <span id="sms-count">1</span></span> |
        <span>زبان: <span id="language">فارسی</span></span>
    </div>
</div>

                {{-- <div class="mb-3">
                    <label>تعداد پیامک‌ها: </label>
                    <span id="sms-count">0</span>
                </div>

                <div class="mb-3">
                    <label>مانده اعتبار: </label>
                    <span id="balance">{{ auth()->user()->sms_balance ?? 0 }}</span> پیامک
                </div> --}}

                <button type="submit" class="btn btn-primary">ارسال</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#numbers').select2({
            tags: true,
            tokenSeparators: [',', '\n'],
            placeholder: "شماره‌ها را وارد کنید (مثال: 09123456789)",
            allowClear: true
        });

        $('#line_number').select2({
            placeholder: "یک خط انتخاب کنید",
            allowClear: true
        });
    });

  function calculateSmsCount() {
        const message = document.getElementById('message').value;
        const isPersian = /[\u0600-\u06FF]/.test(message);
        const smsLength = isPersian || !message ? 70 : 160; // اگر پیام خالی است، فرض فارسی
        const messageLength = message.length;
        const smsCount = Math.ceil(messageLength / smsLength) || 1; // حداقل 1 پیامک

        // به‌روزرسانی تعداد کاراکتر
        document.getElementById('char-count').innerText = messageLength;

        // به‌روزرسانی حداکثر کاراکتر
        document.getElementById('max-chars').innerText = smsLength;

        // به‌روزرسانی تعداد پیامک
        document.getElementById('sms-count').innerText = smsCount;

        // به‌روزرسانی زبان
        document.getElementById('language').innerText = isPersian || !message ? 'فارسی' : 'انگلیسی';
    }
</script>
@endsection