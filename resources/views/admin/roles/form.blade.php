<form method="POST" action="{{ isset($role) ? route('main_admin.roles.update', $role->id) : route('main_admin.roles.store') }}">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif
<div class="row">

    <div class="form-group col-md-6">
        <label for="name">{{ __('اسم الدور') }}</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name ?? '') }}">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="display_name">{{ __('وصف الدور') }}</label>
        <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name', $role->display_name ?? '') }}">
        @error('display_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

    <div class="form-group">
        <label for="permissions">{{ __('الصلاحيات') }}</label>
        <div class="form-check">
            <!-- "Select All" checkbox -->
            <div class="animated-checkbox">
                <label>
                    <input type="checkbox" id="select-all"><span class="label-text">{{ __('تحديد الكل') }}</span>
                </label>
            </div>

            <div class="row">
                <!-- Loop through permissions to display each as a checkbox -->
                @foreach($permissions as $permission)
                    <div class="col-md-4">
                        <div class="animated-checkbox">
                            <label>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       @if(isset($rolePermissions) && in_array($permission->id, $rolePermissions)) checked @endif>
                                <span class="label-text">{{ $permission->perm_label }}</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @error('permissions')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ isset($role) ? __('تحديث') : __('إضافة') }}</button>
</form>

<!-- Script to enable "Select All" functionality -->
@push('js')
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
@endpush
