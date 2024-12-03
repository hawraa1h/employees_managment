@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-key"></i> {{ __('إدارة الأدوار') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{__('قائمة الأدوار')}}</h3>
                        <a href="{{ route('main_admin.roles.create') }}" class="btn float-right btn-primary"><i class="fa fa-plus"></i> إضافة</a>
                    </div>
                    <table class="table text-center table-striped" id="sampleTable">

                    <thead>
                    <tr>
                        <th>{{ __('الرقم') }}</th>
                        <th>{{ __('الاسم') }}</th>
                        <th>{{ __('اسم الدور') }}</th>
                        <th>{{ __('عدد الصلاحيات') }}</th>

                        <th>{{ __('العمليات') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->display_name }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->permissions->count() }}</td> <!-- Display the number of permissions -->

                            <td>
                                <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#permissionsModal{{$role->id}}">
                                    <i class="fa fa-list"></i> {{__('عرض الصلاحيات')}}
                                </a>
                                <a href="{{ route('main_admin.roles.edit', $role->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>{{__('تعديل')}}</a>

                                <a onclick="confirmation('trash{{$role->id}}', '')" class="btn btn-danger btn-sm" href="#"><i class="fa fa-trash-o mr-0"></i> {{__('حذف')}} </a>
                                <form id="trash{{$role->id}}" method="post" action="{{route('main_admin.roles.destroy', $role->id)}}">@csrf @method('DELETE')</form>

                            </td>
                        </tr>
                        <!-- Modal to display permissions -->
                        <div class="modal fade" id="permissionsModal{{$role->id}}" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel{{$role->id}}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permissionsModalLabel{{$role->id}}">{{ __('الصلاحيات للدور: ') }} {{ $role->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <ul>
                                            @foreach($role->permissions as $permission)
                                                <li>{{ $permission->perm_label }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('إغلاق') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </main>
@endsection
