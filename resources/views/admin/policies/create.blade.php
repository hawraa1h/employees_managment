@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-key"></i> {{__('إضافة سياسة جديدة')}}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{__('إضافة سياسة')}}</h3>
                        <a href="{{ route('main_admin.policies.index') }}" class="btn float-right btn-danger"><i class="fa fa-arrow-left"></i> رجوع </a>
                    </div>
                    @include('admin.policies.form', ['policy' => null])
                </div>
            </div>
        </div>
    </main>
@endsection
