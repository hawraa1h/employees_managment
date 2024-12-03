@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1>{{ __('إدارة المعايير') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{ __('قائمة المعايير') }}</h3>
                        @if(!hasRole('normal'))

                        <a href="{{ route('main_admin.standards.create') }}" class="btn float-right btn-primary"><i class="fa fa-plus"></i> إضافة</a>
                        @endif
                    </div>
                    <table class="table table-striped" id="sampleTable">
                        <thead>
                        <tr>
                            <th>{{ __('العنوان') }}</th>
{{--                            <th>{{ __('القسم') }}</th>--}}
                            <th>{{ __('الحالة') }}</th>
                            <th>{{ __('ملف المراجعة') }}</th>
                            <th>{{ __('تاريخ المراجعة المتوقع') }}</th>
                            <th>{{ __('تاريخ الإضافة') }}</th>
                            @if(hasRole('admin'))
                                <th>{{ __('الاعتماد') }}</th>
                            @endif
                            <th>{{ __('العمليات') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($policies as $policy)
                            <tr>
                                <td>{{ $policy->title }}</td>
{{--                                <td>{{ $policy->department->name }}</td>--}}
                                <td>
                                    {{ $policy->status == 'pending' ? __('فى انتظار المراجعة') : __('تمت المراجعة') }}
                                </td>
                                <td>
                                    @if ($policy->file_path)
                                        <a href="{{ asset($policy->file_path) }}" target="_blank" class="btn btn-link">{{ __('معاينة') }}</a>
                                    @else
                                        <span>{{ __('لا يوجد ملف') }}</span>
                                    @endif
                                </td>
                                <td>{{ $policy->expected_review_date }}</td>
                                <td>{{ $policy->created_at->format('Y-m-d') }}</td>
                                @if(hasRole('admin'))
                                    <td>
                                        <input type="checkbox" class="policy-checkbox" data-id="{{ $policy->id }}" {{ $policy->checked ? 'checked' : '' }}>
                                    </td>
                                @endif
                                <td>
                                    @if(!hasRole('normal'))

                                    <a href="{{ route('main_admin.standards.edit', $policy->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i> {{__('تعديل')}}
                                    </a>
                                    <a onclick="confirmation('trash{{$policy->id}}', '')" class="btn btn-danger btn-sm" href="#">
                                        <i class="fa fa-trash-o mr-0"></i> {{__('حذف')}}
                                    </a>
                                    <form id="trash{{$policy->id}}" method="post" action="{{route('main_admin.standards.destroy', $policy->id)}}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                    <!-- Button to trigger the modal -->
                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#policyModal{{$policy->id}}">
                                        <i class="fa fa-eye"></i> {{__('معاينة')}}
                                    </button>

                                    @if ($policy->status == 'pending')
                                        @if(hasPermission('review_standards') && !hasRole('normal'))
                                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#reviewModal{{$policy->id}}">
                                            <i class="fa fa-check"></i> {{__('مراجعة')}}
                                        </button>
                                        <!-- Modal for review -->
                                        <div class="modal fade" id="reviewModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel{{$policy->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reviewModalLabel{{$policy->id}}">{{ __('مراجعة السياسة') }}: {{ $policy->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('main_admin.standards.review', $policy->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p><strong>{{ __('العنوان') }}:</strong> {{ $policy->title }}</p>
{{--                                                            <p><strong>{{ __('القسم') }}:</strong> {{ $policy->department->name }}</p>--}}
                                                            <p><strong>{{ __('ملف المراجعة الحالي') }}:</strong>
                                                                <a href="{{ asset($policy->file_path) }}" target="_blank">{{ __('معاينة الملف') }}</a>
                                                            </p>

                                                            <!-- File Re-upload Option -->
                                                            <div class="form-group">
                                                                <label for="file_path">{{ __('إعادة رفع الملف (اختياري)') }}</label>
                                                                <input type="file" name="file_path" class="form-control" accept=".pdf,.doc,.docx">
                                                                <small class="form-text text-muted">{{ __('يمكنك إعادة رفع ملف جديد بصيغة PDF أو Word') }}</small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="review_notes">{{ __('ملاحظات المراجعة') }}</label>
                                                                <textarea name="review_notes" id="review_notes" class="form-control" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('إغلاق') }}</button>
                                                            <button type="submit" class="btn btn-primary">{{ __('مراجعة') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endif

                                    @if ($policy->status == 'reviewed')
                                        @if(hasPermission('audit_standards') && !hasRole('normal'))
                                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#auditModal{{$policy->id}}">
                                            <i class="fa fa-check"></i> {{__('تدقيق')}}
                                        </button>
                                        <!-- Audit Modal -->
                                        <div class="modal fade" id="auditModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel{{$policy->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="auditModalLabel{{$policy->id}}">{{ __('تدقيق السياسة') }}: {{ $policy->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="{{ route('main_admin.standards.audit', $policy->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p><strong>{{ __('العنوان') }}:</strong> {{ $policy->title }}</p>
{{--                                                            <p><strong>{{ __('القسم') }}:</strong> {{ $policy->department->name }}</p>--}}
                                                            <p><strong>{{ __('ملف المراجعة الحالي') }}:</strong>
                                                                <a href="{{ asset($policy->file_path) }}" target="_blank">{{ __('معاينة الملف') }}</a>
                                                            </p>

                                                            <!-- File Re-upload Option -->
                                                            <div class="form-group">
                                                                <label for="file_path">{{ __('إعادة رفع الملف (اختياري)') }}</label>
                                                                <input type="file" name="file_path" class="form-control" accept=".pdf,.doc,.docx">
                                                                <small class="form-text text-muted">{{ __('يمكنك إعادة رفع ملف جديد بصيغة PDF أو Word') }}</small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="audit_notes">{{ __('ملاحظات التدقيق') }}</label>
                                                                <textarea name="audit_notes" id="audit_notes" class="form-control" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('إغلاق') }}</button>
                                                            <button type="submit" class="btn btn-primary">{{ __('تدقيق') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @endif


                                    <!-- Modal for policy preview -->
                                    <div class="modal fade" id="policyModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="policyModalLabel{{$policy->id}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="policyModalLabel{{$policy->id}}">{{ __('معاينة السياسة') }}: {{ $policy->title }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>{{ __('العنوان') }}:</strong> {{ $policy->title }}</p>
{{--                                                    <p><strong>{{ __('القسم') }}:</strong> {{ $policy->department->name }}</p>--}}
                                                    <p><strong>{{ __('الحالة') }}:</strong>
                                                        @if ($policy->status == 'pending')
                                                            {{ __('فى انتظار المراجعة') }}
                                                        @elseif ($policy->status == 'reviewed')
                                                            {{ __('تمت المراجعة') }}
                                                        @elseif ($policy->status == 'audited')
                                                            {{ __('تم التدقيق') }}
                                                        @endif
                                                    </p>
                                                    <p><strong>{{ __('تاريخ المراجعة المتوقع') }}:</strong> {{ $policy->expected_review_date }}</p>
                                                    <p><strong>{{ __('تاريخ التدقيق المتوقع') }}:</strong> {{ $policy->expected_audit_date }}</p>
                                                    <p><strong>{{ __('تاريخ الإضافة') }}:</strong> {{ $policy->created_at->format('Y-m-d') }}</p>

                                                    <!-- Review Information Box -->
                                                    @if ($policy->status == 'reviewed' || $policy->status == 'audited')
                                                        <div class="review-info-box" style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                                                            <h6><strong>{{ __('تفاصيل المراجعة') }}</strong></h6>
                                                            <p><strong>{{ __('مراجعة في') }}:</strong> {{ $policy->reviewed_at }}</p>
                                                            <p><strong>{{ __('مراجعة بواسطة') }}:</strong> {{ $policy->reviewer->name ?? __('غير متوفر') }}</p>
                                                            <p><strong>{{ __('ملاحظات المراجعة') }}:</strong> {{ $policy->review_notes }}</p>
                                                        </div>
                                                    @else
                                                        <p><strong>{{ __('تفاصيل المراجعة') }}:</strong> {{ __('لم تتم المراجعة بعد') }}</p>
                                                    @endif

                                                    <!-- Audit Information Box -->
                                                    @if ($policy->status == 'audited')
                                                        <div class="audit-info-box" style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                                                            <h6><strong>{{ __('تفاصيل التدقيق') }}</strong></h6>
                                                            <p><strong>{{ __('تدقيق في') }}:</strong> {{ $policy->audited_at }}</p>
                                                            <p><strong>{{ __('تدقيق بواسطة') }}:</strong> {{ $policy->auditor->name ?? __('غير متوفر') }}</p>
                                                            <p><strong>{{ __('ملاحظات التدقيق') }}:</strong> {{ $policy->audit_notes }}</p>
                                                        </div>
                                                    @endif
                                                    @if ($policy->notes_by_employee)
                                                        <div class="audit-info-box" style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 20px;">
                                                            <h6><strong>ملاحظات الموظف</strong></h6>
                                                            <p>{{ $policy->notes_by_employee }}</p>

                                                        </div>
                                                    @endif
                                                    <!-- Review File -->
                                                    @if ($policy->file_path)
                                                        <p class="mt-4"><strong>{{ __('ملف المراجعة') }}:</strong>
                                                            <a href="{{ asset($policy->file_path) }}" target="_blank">{{ __('معاينة الملف') }}</a>
                                                        </p>
                                                    @endif

                                                    @if(hasRole('normal'))
                                                        <form action="{{ route('main_admin.policies.updateNotes', $policy->id) }}" method="POST">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="notes_by_employee">ملاحظات الموظف</label>
                                                                <textarea class="form-control" id="notes_by_employee" required name="notes_by_employee" rows="4" placeholder="أدخل ملاحظاتك هنا..."></textarea>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">إرسال الملاحظات</button>
                                                        </form>
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('إغلاق') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js')
    <script>
        $(document).on('change', '.policy-checkbox', function () {
            let policyId = $(this).data('id');
            let checked = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: `policies/${policyId}/check`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    checked: checked,
                },
                success: function (response) {
                    alert(response.message);
                },
                error: function () {
                    alert('حدث خطأ أثناء تحديث الحالة.');
                }
            });
        });
    </script>
@endpush
