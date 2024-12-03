<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تحديث كلمة المرور</title>
    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/ar.css') }}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo';
        }
        .material-half-bg .cover {
            background-color: #ffffff;
            height: 50vh;
        }
        .material-half-bg {
            height: 100vh;
            background-color: #ffffff;
        }
        .login-content .logo {
            margin-bottom: 16px;
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .btn-primary {
            color: #FFF;
            background-color: #8a2a2f;
            border-color: #8a2a2f;
        }
        .login-content .login-box {
            min-height: 461px;
        }
    </style>
</head>
<body>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        @php
            toastr()->error($error);
        @endphp
    @endforeach
@endif
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <img height="100px" src="{{ asset('logo.png') }}">
    </div>
    <div class="login-box">
        <form id="passwordUpdateForm" class="login-form" action="{{ route('main_admin.password_update.submit') }}" method="post">
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>{{ __('تحديث كلمة المرور') }}</h3>
            <small class="form-text text-muted  text-center">
                <a href="{{route('generate_strong_paswword')}}" target="_blank">{{ __('تعرف على كيفية إنشاء كلمة مرور قوية') }}</a>
            </small>
            <div class="form-group">
                <label class="control-label">{{ __('كلمة المرور الحالية') }}</label>
                <input autocomplete="current-password" class="form-control" type="password" name="current_password" required>
            </div>

            <div class="form-group">
                <label class="control-label">{{ __('كلمة المرور الجديدة') }}</label>
                <input autocomplete="new-password" class="form-control" type="password" name="new_password" required>
                <small class="form-text text-muted">{{ __('كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم وحرف خاص.') }}</small>
            </div>

            <div class="form-group">
                <label class="control-label">{{ __('تأكيد كلمة المرور الجديدة') }}</label>
                <input autocomplete="new-password" class="form-control" type="password" name="new_password_confirmation" required>
            </div>

            <div class="form-group btn-container">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-check fa-lg fa-fw"></i>{{ __('تحديث') }}
                </button>
            </div>
        </form>
    </div>
</section>
<!-- Essential javascripts -->
<script src="{{ asset('admin/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('admin/js/popper.min.js') }}"></script>
<script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/js/main.js') }}"></script>
<script src="{{ asset('admin/js/plugins/pace.min.js') }}"></script>
</body>
</html>
