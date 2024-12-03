@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-key"></i> {{__('إدارة الصلاحيات')}}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{__('قائمة الصلاحيات')}}</h3>
                        <a href="{{ route('main_admin.permissions.create') }}" class="btn float-right btn-primary"><i class="fa fa-plus"></i> إضافة</a>
                    </div>
                    <table class="table table-striped" id="sampleTable">
                        <thead>
                        <tr>
                            <th>{{__('الرقم')}}</th>
                            <th>{{__('اسم الصلاحية')}}</th>
                            <th>{{__('وصف الصلاحية')}}</th>
                            <th>{{__('العمليات')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->perm_name }}</td>
                                <td>{{ $permission->perm_label }}</td>
                                <td>
                                    <a href="{{ route('main_admin.permissions.edit', $permission->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>{{__('تعديل')}}</a>

                                    <a onclick="confirmation('trash{{$permission->id}}', '')" class="btn btn-danger btn-sm" href="#"><i class="fa fa-trash-o mr-0"></i> {{__('حذف')}} </a>
                                    <form id="trash{{$permission->id}}" method="post" action="{{route('main_admin.permissions.destroy', $permission->id)}}">@csrf @method('DELETE')</form>
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
