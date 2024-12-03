@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-building"></i> {{ __('تحديث الاداراة') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="d-flex mainAdd">
                        <h3 class="tile-title">{{ __('تحديث الاداراة') }}</h3>
                        <a href="{{ route('main_admin.departments.index') }}" class="btn float-right btn-danger"><i class="fa fa-arrow-left"></i> رجوع </a>
                    </div>
                    @include('admin.departments.form', ['department' => $department])
                </div>
            </div>
        </div>
    </main>
@endsection
