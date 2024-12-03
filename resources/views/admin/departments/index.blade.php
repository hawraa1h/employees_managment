@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-building"></i> {{ __('الأدارات') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{ __('قائمة الأدارات') }}</h3>
                        <a href="{{ route('main_admin.departments.create') }}" class="btn float-right btn-primary">
                            <i class="fa fa-plus"></i> {{ __('إضافة إدارة') }}
                        </a>
                    </div>
                    <table class="table table-striped" id="sampleTable">
                        <thead>
                        <tr>
                            <th>{{ __('الرقم') }}</th>
                            <th>{{ __('اسم الأداراة') }}</th>
                            <th>{{ __('الوصف') }}</th>
                            <th>{{ __('العمليات') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($departments as $index=>$department)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->description }}</td>
                                <td>
                                    <a href="{{ route('main_admin.departments.edit', $department->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i> {{ __('تعديل') }}
                                    </a>
                                    <a onclick="confirmation('trash{{$department->id}}', '')" class="btn btn-danger btn-sm" href="#">
                                        <i class="fa fa-trash-o mr-0"></i> {{ __('حذف') }}
                                    </a>
                                    <form id="trash{{$department->id}}" method="post" action="{{ route('main_admin.departments.destroy', $department->id) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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

