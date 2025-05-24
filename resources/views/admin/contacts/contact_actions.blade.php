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
