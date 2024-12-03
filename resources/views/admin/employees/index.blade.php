@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-users"></i> {{ __('إدارة الموظفين') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{__('قائمة الموظفين')}}</h3>
                        <a href="{{ route('main_admin.employees.create') }}" class="btn float-right btn-primary">
                            <i class="fa fa-plus"></i> {{ __('إضافة موظف') }}
                        </a>
                    </div>
                    <table class="table table-striped" id="sampleTable">
                        <thead>
                        <tr>
                            <th>{{ __('صورة الموظف') }}</th>
                            <th>{{ __('اسم الموظف') }}</th>
                            <th>{{ __('رقم الموظف') }}</th>
                            <th>{{ __('القسم') }}</th>
                            <th>{{ __('الدور') }}</th>
                            <th>{{ __('العمليات') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($employees as $employee)
{{--                            @if($employee->id != auth()->id())--}}
                            <tr>
                                <td> <img src="{{ $employee->image_path }}" class="img-thumbnail" style="height: 70px;"></td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->emp_number }}</td>
                                <td>{{ optional($employee->department)->name }}</td>
                                <td>{{ optional($employee->role)->display_name }}</td>
                                <td>

                                    <!-- View Button -->
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewEmployeeModal{{ $employee->id }}">
                                        <i class="fa fa-eye"></i> {{ __('عرض') }}
                                    </button>

                                    <a href="{{ route('main_admin.employees.edit', $employee->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                    </a>
                                    @if($employee->id != auth()->id())
                                    <a onclick="confirmation('trash{{ $employee->id }}', '')" class="btn btn-danger btn-sm" href="#">
                                        <i class="fa fa-trash-o mr-0"></i> {{ __('حذف') }}
                                    </a>
                                    <form id="trash{{ $employee->id }}" method="post" action="{{ route('main_admin.employees.destroy', $employee->id) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal for Viewing Employee Details -->
                            <div class="modal fade" id="viewEmployeeModal{{ $employee->id }}" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewEmployeeModalLabel{{ $employee->id }}">{{ __('عرض بيانات الموظف') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <!-- Employee Image -->
                                            <img src="{{ $employee->image_path }}" class="img-thumbnail mb-3 imgEmp">

                                            <!-- Employee Name -->
                                            <h4>{{ $employee->name }}</h4>
                                            <hr>

                                            <!-- Employee Details -->
                                            <ul class="list-unstyled listEmp">
                                                <li><strong>{{ __('رقم الهوية') }}:</strong> {{ $employee->id_number }}</li>
                                                <li><strong>{{ __('رقم الموظف') }}:</strong> {{ $employee->emp_number }}</li>
                                                <li><strong>{{ __('رقم الهاتف') }}:</strong> {{ $employee->mobile }}</li>
                                                <li><strong>{{ __('البريد الإلكتروني') }}:</strong> {{ $employee->email }}</li>
                                                <li><strong>{{ __('العنوان') }}:</strong> {{ $employee->address ?? __('لا يوجد') }}</li>
                                                <li><strong>{{ __('القسم') }}:</strong> {{ optional($employee->department)->name }}</li>
                                                <li><strong>{{ __('الدور') }}:</strong> {{ optional($employee->role)->name }}</li>
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('إغلاق') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                            @endif--}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
