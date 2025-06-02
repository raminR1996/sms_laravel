<a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-icon btn-warning" title="ویرایش تنظیم">
    <i class="fas fa-edit"></i>
</a>
<form action="{{ route('admin.settings.destroy', $setting->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-icon btn-danger" title="حذف تنظیم" onclick="return confirm('آیا مطمئن هستید؟')">
        <i class="fas fa-trash"></i>
    </button>
</form>