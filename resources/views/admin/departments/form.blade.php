<form method="POST" action="{{ isset($department) ? route('main_admin.departments.update', $department->id) : route('main_admin.departments.store') }}">
    @csrf
    @if(isset($department))
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="name">{{ __('اسم القسم') }}</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $department->name ?? '') }}">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="description">{{ __('الوصف') }}</label>
        <textarea class="form-control" id="description" name="description">{{ old('description', $department->description ?? '') }}</textarea>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ isset($department) ? __('تحديث') : __('إضافة') }}</button>
</form>
