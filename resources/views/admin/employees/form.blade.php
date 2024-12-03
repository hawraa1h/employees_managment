<form method="POST" action="{{ isset($employee) ? route('main_admin.employees.update', $employee->id) : route('main_admin.employees.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($employee))
        @method('PUT')
    @endif

    <div class="row">
        <div class="form-group col-md-6">
            <label for="name">{{ __('اسم الموظف') }} *</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->name ?? '') }}">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="emp_number">{{ __('رقم الموظف') }} *</label>
            <input type="text" class="form-control" id="emp_number" name="emp_number" value="{{ old('emp_number', $employee->emp_number ?? '') }}">
            <small class="form-text text-muted">{{ __('رقم الموظف يجب أن يكون 10 أرقام. مثال: 1234567890') }}</small>
            @error('emp_number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

{{--        <div class="form-group col-md-6">--}}
{{--            <label for="id_number">{{ __('رقم الهوية') }} *</label>--}}
{{--            <input type="text" class="form-control" id="id_number" name="id_number" value="{{ old('id_number', $employee->id_number ?? '') }}">--}}
{{--            <small class="form-text text-muted">{{ __('رقم الهوية يجب أن يكون 10 أرقام. مثال: 1234567890') }}</small>--}}
{{--            @error('id_number')--}}
{{--            <div class="text-danger">{{ $message }}</div>--}}
{{--            @enderror--}}
{{--        </div>--}}

        <div class="form-group col-md-6">
            <label for="mobile">{{ __('الهاتف') }} *</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $employee->mobile ?? '') }}">
            @error('mobile')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">{{ __('الهاتف يجب أن يكون بصيغة +966555555555 أو 0555555555') }}</small>
        </div>

        <div class="form-group col-md-6">
            <label for="email">{{ __('البريد الإلكتروني') }}</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}">
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="password">{{ __('كلمة المرور') }} @if(!isset($employee))*@endif</label>
            <input type="password" class="form-control" id="password" name="password">
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                <a href="{{route('generate_strong_paswword')}}" target="_blank">{{ __('تعرف على كيفية إنشاء كلمة مرور قوية') }}</a>
            </small>
            <small class="form-text text-muted">{{ __('كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم، وحرف خاص مثل @ $ ! % * ? &') }}</small>
        </div>

        <div class="form-group col-md-6">
            <label for="password_confirmation">{{ __('تأكيد كلمة المرور') }} *</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            @error('password_confirmation')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="department_id">{{ __('القسم') }} *</label>
            <select class="form-control" id="department_id" name="department_id">
                <option value="">{{ __('اختر القسم') }}</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="role_id">{{ __('الدور') }} *</label>
            <select class="form-control" id="role_id" name="role_id">
                <option value="">{{ __('اختر الدور') }}</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id ?? '') == $role->id ? 'selected' : '' }}>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="image">{{ __('صورة الموظف') }}</label>
            <input type="file" class="form-control-file" id="image" name="image" onchange="previewImage(event)">
            <img id="image-preview" src="{{ isset($employee) ? $employee->image_path : '' }}" style="max-width: 200px; margin-top: 10px;">
            @error('image')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary">{{ isset($employee) ? __('تحديث') : __('إضافة') }}</button>
</form>

<!-- Image Preview Script -->
@push('js')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image-preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
