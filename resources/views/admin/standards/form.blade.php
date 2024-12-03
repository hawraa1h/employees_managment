<style>
    .custom-file-upload {
        border: 2px dashed #007bff;
        display: inline-block;
        padding: 14px;
        width: 100%;
        text-align: center;
        cursor: pointer;
        color: #902223;
        background: url('{{asset('up.jpg')}}') no-repeat center center;
        background-size: 85px 85px;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }

    .custom-file-upload:hover {
        background-color: #f8f9fa;
    }

    .custom-file-upload:after {
        content: 'قم بسحب الملف هنا أو انقر لرفع ملف';
        display: block;
        margin-top: 110px;
        font-size: 16px;
        color: #007bff;
    }

    .file-name {
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
    }
</style>

<form enctype="multipart/form-data" method="POST" action="{{ isset($policy) ? route('main_admin.standards.update', $policy->id) : route('main_admin.policies.store') }}">
    @csrf
    @if(isset($policy))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Custom File Upload -->
        <div class="form-group col-md-12">
            <label for="file_path">{{ __('الملف') }} *</label>
            <label class="custom-file-upload">
                <input type="file" name="file_path" id="file_path" accept=".pdf,.doc,.docx" {{ !isset($policy) ? 'required' : '' }}>
            </label>
            <small class="form-text text-muted">{{ __('يُقبل فقط ملفات PDF image Word') }}</small>
            <div id="file-name" class="file-name"></div> <!-- Display file name after selection -->

            @if (isset($policy) && $policy->file_path)
                <a href="{{ asset($policy->file_path) }}" target="_blank" class="btn btn-link">{{ __('معاينة الملف الحالي') }}</a>
            @endif
        </div>

        <!-- Title -->
        <div class="form-group col-md-6">
            <label for="title">{{ __('العنوان') }} *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $policy->title ?? '') }}" required>
        </div>

        <!-- Department Multi-select Dropdown -->
        <div class="form-group col-md-6">
            <label for="department_ids">{{ __('الأقسام') }} *</label>
            <select name="department_ids[]" class="form-control select2" multiple required>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ (collect(old('department_ids', $policy &&$policy->departments ? $policy->departments->pluck('id') : []))->contains($department->id)) ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Employee Multi-select Dropdown -->
        <div class="form-group col-md-6">
            <label for="employee_ids">{{ __('الموظفين') }} *</label>
            <select name="employee_ids[]" class="form-control select2" multiple required>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ (collect(old('employee_ids', $policy && $policy->employees ? $policy->employees->pluck('id'): []))->contains($employee->id)) ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Expected Review Date -->
        <div class="form-group col-md-6">
            <label for="expected_review_date">{{ __('تاريخ المراجعة المتوقع') }} *</label>
            <input type="date" name="expected_review_date" class="form-control" min="{{ now()->format('Y-m-d') }}" value="{{ old('expected_review_date', $policy->expected_review_date ?? '') }}" required>
        </div>

        <!-- Expected Audit Date -->
        <div class="form-group col-md-6">
            <label for="expected_audit_date">{{ __('تاريخ التدقيق المتوقع') }} *</label>
            <input type="date" name="expected_audit_date" class="form-control" min="{{ now()->format('Y-m-d') }}" value="{{ old('expected_audit_date', $policy->expected_audit_date ?? '') }}" required>
        </div>

        <div class="form-group col-md-12">
            <button type="submit" class="btn btn-primary">{{ isset($policy) ? __('تحديث') : __('إضافة') }}</button>
        </div>
    </div>
</form>

@push('js')
    <script>
        document.getElementById('file_path').addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
@endpush
