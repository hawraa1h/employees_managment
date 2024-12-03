@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-key"></i> {{ __('إضافة دور جديد') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{ __('إضافة دور') }}</h3>

                        <a href="{{ route('main_admin.roles.index') }}" class="btn float-right btn-danger"><i class="fa fa-arrow-left"></i> رجوع </a>
                    </div>
                    @include('admin.roles.form')
                </div>
            </div>
        </div>
    </main>
@endsection
