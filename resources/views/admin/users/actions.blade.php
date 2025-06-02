<a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-warning" title="ویرایش کاربر">
    <i class="fas fa-edit"></i>
</a>
<form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-icon btn-danger" title="حذف کاربر" onclick="return confirm('آیا مطمئن هستید؟')">
        <i class="fas fa-trash"></i>
    </button>
</form>