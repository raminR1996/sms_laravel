@extends('layouts.layout')

@section('title', 'ارسال پیامک گروهی')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-selection--multiple, .select2-selection--single {
        min-height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
    }
    .select2-selection__choice {
        background-color: #007bff !important;
        color: #fff !important;
        border: none !important;
    }
    .select2-selection__choice__remove {
        color: #fff !important;
    }
    .select2-results__option[data-line-type="service"] {
        color: #8B4513 !important;
    }
    .select2-results__option[data-line-type="advertising"] {
        color: #FF4500 !important;
    }
    .sms-info {
        font-size: 0.9rem;
        color: #555;
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        margin-top: 0.5rem;
    }
    .sms-info span {
        font-weight: 500;
    }
    .sms-info #char-count, .sms-info #sms-count, .sms-info #language {
        color: #007bff;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center mb-4">ارسال پیامک گروهی</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('send.sms.group.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="line_number" class="form-label">شماره خط ارسالی:</label>
                    <select name="line_number" id="line_number" class="form-control select2" required>
                        <option value="" disabled selected>یک خط انتخاب کنید</option>
                        @foreach($lines as $line)
                            <option 
                                value="{{ $line->line_number }}" 
                                data-line-type="{{ $line->line_type }}"
                            >
                                {{ $line->line_number }} ({{ $line->operator_name }} - {{ $line->line_type === 'service' ? 'خدماتی' : 'تبلیغاتی' }})
                            </option>
                        @endforeach
                    </select>
                    @error('line_number')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="village_ids" class="form-label">روستاها:</label>
                    <select name="village_ids[]" id="village_ids" class="form-control select2" multiple="multiple" required>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}">
                                {{ $village->name }} ({{ $village->contacts_count }} شماره)
                            </option>
                        @endforeach
                    </select>
                    @error('village_ids')
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

                <div class="mb-3">
                    <label>مانده اعتبار: </label>
                    <span id="balance">{{ auth()->user()->sms_balance ?? 0 }}</span> پیامک
                </div>

                <button type="submit" class="btn btn-primary">ثبت درخواست</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#village_ids').select2({
            placeholder: "روستاها را انتخاب کنید",
            allowClear: true
        });

        $('#line_number').select2({
            placeholder: "یک خط انتخاب کنید",
            allowClear: true,
            templateResult: function(data) {
                if (!data.element) {
                    return data.text;
                }
                var $option = $('<span>' + data.text + '</span>');
                var lineType = $(data.element).data('line-type');
                if (lineType) {
                    $option.attr('data-line-type', lineType);
                }
                return $option;
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        calculateSmsCount();
    });

    function calculateSmsCount() {
        const message = document.getElementById('message').value;
        const isPersian = /[\u0600-\u06FF]/.test(message);
        const smsLength = isPersian || !message ? 70 : 160;
        const messageLength = message.length;
        const smsCount = Math.ceil(messageLength / smsLength) || 1;

        document.getElementById('char-count').innerText = messageLength;
        document.getElementById('max-chars').innerText = smsLength;
        document.getElementById('sms-count').innerText = smsCount;
        document.getElementById('language').innerText = isPersian || !message ? 'فارسی' : 'انگلیسی';
    }
</script>
@endsection