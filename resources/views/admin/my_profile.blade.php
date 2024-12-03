@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-edit"></i> {{__('Update Profile')}} </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">{{__('Update Profile')}}</h3>
                    <form class="row" method="post" action="{{route('main_admin.my_profile')}}">
                        @csrf
                        <div class="form-group col-md-6">
                            <label for="Name">{{__('Full Name')}}</label>
                            <input class="form-control" id="Name" type="text" name="name" value="{{$profile->name}}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="Mail">{{__('E-Mail')}}</label>
                            <input class="form-control" id="Mail" type="email" name="email" value="{{$profile->email}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="Password">{{__('Password')}}</label>
                            <input class="form-control" autocomplete="new-password" id="Password" type="password" name="password">
                            <small class="form-text text-muted">
                                <a href="{{route('generate_strong_paswword')}}" target="_blank">{{ __('تعرف على كيفية إنشاء كلمة مرور قوية') }}</a>
                            </small>
                            <small class="form-text text-muted">{{ __('كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم، وحرف خاص مثل @ $ ! % * ? &') }}</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="Confirmation">{{__('Password Confirmation')}}</label>
                            <input class="form-control" autocomplete="new-password" id="Confirmation" type="password" name="password_confirmation">
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary"> حفظ التغيرات <i class="fa fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
