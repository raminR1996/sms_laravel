<div class="d-flex align-items-center gap-2">
    @if($report->status !== 'approved')
        <span class="text-muted">در انتظار تأیید</span>
    @else
        <a href="{{ route('group.reports.details', $report->id) }}" class="btn btn-secondary btn-sm" 
           title="مشاهده جزئیات و به‌روزرسانی وضعیت">
            <i class="fas fa-eye"></i>
        </a>
    @endif
</div>