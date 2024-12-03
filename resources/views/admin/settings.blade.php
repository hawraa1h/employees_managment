@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-cog"></i> {{__('تحديث الإعدادات')}} </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">{{__('تحديث الإعدادات')}}</h3>
                    <form class="row" method="post" action="{{ route('main_admin.settings_update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-md-6">
                            <label for="WebsiteName">{{__('اسم الموقع')}}</label>
                            <input class="form-control" id="WebsiteName" type="text" name="website_name" value="{{ old('website_name', $settings->website_name ?? '') }}">
                            @error('website_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Logo">{{__('شعار الموقع')}}</label>
                            <input class="form-control-file" id="Logo" type="file" name="logo">
                            @if($settings && $settings->logo)
                                <img src="{{ asset($settings->logo) }}" alt="Logo" style="max-height: 100px; margin-top: 10px;">
                            @endif
                            @error('logo')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="SessionPeriod">{{__('مدة الجلسة (بالدقائق)')}}</label>
                            <input class="form-control" id="SessionPeriod" type="number" name="session_period" value="{{ old('session_period', $settings->session_period ?? '') }}">
                            @error('session_period')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="PasswordPeriod">{{__('مدة انتهاء كلمة المرور (بالأيام)')}}</label>
                            <input class="form-control" id="PasswordPeriod" type="number" name="password_period" value="{{ old('password_period', $settings->password_period ?? '') }}">
                            @error('password_period')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">{{__('حفظ التغييرات')}} <i class="fa fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
