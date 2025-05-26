<div class="d-flex align-items-center gap-2">
    <!-- دکمه به‌روزرسانی وضعیت -->
    <a href="{{ route('reports.update.status', $report->id) }}" class="btn btn-info btn-sm" title="به‌روزرسانی وضعیت">
        <i class="fas fa-sync-alt"></i>
    </a>
    <!-- دکمه نمایش شماره‌ها و وضعیت‌ها -->
    <button type="button" class="btn btn-secondary btn-sm show-status-modal" 
            data-report-id="{{ $report->id }}"
            data-numbers="{{ json_encode($report->numbers) }}"
            data-statuses="{{ json_encode($report->statuses) }}"
            data-datetimes="{{ json_encode($report->datetimes) }}"
            title="مشاهده شماره‌ها و وضعیت">
        <i class="fas fa-eye"></i>
    </button>
</div>