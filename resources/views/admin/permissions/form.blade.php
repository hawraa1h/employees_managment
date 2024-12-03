<form method="POST" action="{{ isset($permission) ? route('main_admin.permissions.update', $permission->id) : route('main_admin.permissions.store') }}">
    @csrf
    @if(isset($permission))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="perm_name">{{__('اسم الصلاحية')}}</label>
        <input type="text" class="form-control" id="perm_name" name="perm_name" value="{{ old('perm_name', $permission->perm_name ?? '') }}">
        @error('perm_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="perm_label">{{__('وصف الصلاحية')}}</label>
        <input type="text" class="form-control" id="perm_label" name="perm_label" value="{{ old('perm_label', $permission->perm_label ?? '') }}">
        @error('perm_label')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ isset($permission) ? __('تحديث') : __('إضافة') }}</button>
</form>
