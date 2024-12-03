<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" dir="{{app()->getLocale() == 'ar' ? 'rtl' : 'ltr'}}">
<head>
    <meta name="description" content="">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>  اداره الموظفيين | امانة الأحساء</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@if(app()->getLocale() == 'ar')
        <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'>
        <link rel="stylesheet" type="text/css" href="{{asset('admin/css/ar.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{asset('admin/css/main.css')}}">
    @endif

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/confirm.css')}}">

    @stack('css')
</head>
<body class="app sidebar-mini rtl">
@if ($errors->any())
    @foreach ($errors->all() as $error)
        @php
            toastr()->error($error);
        @endphp
    @endforeach
@endif
<header class="app-header"><a class="app-header__logo" href="#">
        <img height="66px" src="{{asset('logo.png')}}" class="logo" alt="" />
    </a>
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <li class="app-search">
            <input class="app-search__input" type="search" placeholder="Search">
            <button class="app-search__button"><i class="fa fa-search"></i></button>
        </li>
        <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
                <i class="fa fa-bell"></i>
            </a>
            <ul class="app-notification dropdown-menu dropdown-menu-right">
                <li class="app-notification__title">الإشعارات ({{ count(getTodayPolicies()) + count(getTodayStandards()) }})</li>
                <div class="app-notification__content">
                    @foreach(getTodayPolicies() as $policy)
                        <li>
                            <a class="app-notification__item" href="#">
                                {{ $policy->title }} - تمت إضافتها فى {{$policy->created_at}}
                            </a>
                        </li>
                    @endforeach
                    @foreach(getTodayStandards() as $standard)
                        <li>
                            <a class="app-notification__item" href="#">
                                {{ $standard->title }} - تمت إضافتها فى {{$standard->created_at}}
                            </a>
                        </li>
                    @endforeach
                </div>
            </ul>
        </li>

        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i
                    class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="#" onclick="confirmationLogout('FormLogout')"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                <form id="FormLogout" class="d-none" action="{{route('main_admin.logout')}}" method="post">@csrf</form>
            </ul>
        </li>
    </ul>
</header>
<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img height="50px;" class="app-sidebar__user-avatar" src="{{auth()->user()->image_path}}" alt="User Image">
        <div>
            <p style="    font-size: 14px;margin-bottom: 9px;" class="app-sidebar__user-name">مرحبا بك : {{auth()->user()->name}}  </p>

            <p style="    font-size: 11px;" class="app-sidebar__user-designation">{{auth()->user()->role ? auth()->user()->role->display_name :'Admin'}}</p>
        </div>
    </div>
    <ul class="app-menu">
        <li>
            <a class="app-menu__item {{isNavbarActive('home')}}" href="{{route('main_admin.home')}}">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">{{__('Dashboard')}}</span>
            </a>
        </li>
        <li>
            <a class="app-menu__item {{isNavbarActive('my_profile')}}" href="{{route('main_admin.my_profile')}}">
                <i class="app-menu__icon fa fa-user"></i>
                <span class="app-menu__label">{{__('My Profile')}}</span>
            </a>
        </li>
        @if(hasPermission('settings'))
            <li>
                <a class="app-menu__item {{isNavbarActive('settings')}}" href="{{route('main_admin.settings')}}">
                    <i class="app-menu__icon fa fa-cogs"></i>
                    <span class="app-menu__label">الأعدادات</span>
                </a>
            </li>
        @endif
        @if(hasPermission('employees'))
            <li>
                <a class="app-menu__item {{isNavbarActive('employees*')}}" href="{{route('main_admin.employees.index')}}">
                    <i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">الموظفيين</span>
                </a>
            </li>
        @endif


    @if(hasPermission('permissions'))
        <li>
            <a class="app-menu__item {{isNavbarActive('permissions*')}}" href="{{route('main_admin.permissions.index')}}">
                <i class="app-menu__icon fa fa-key"></i><span class="app-menu__label">الصلاحيات</span>
            </a>
        </li>
        @endif

        @if(hasPermission('roles'))
        <li>
            <a class="app-menu__item {{isNavbarActive('roles*')}}" href="{{route('main_admin.roles.index')}}">
                <i class="app-menu__icon fa fa-road"></i><span class="app-menu__label">الأدوار</span>
            </a>
        </li>
        @endif
        @if(hasPermission('departments'))
        <li>
            <a class="app-menu__item {{isNavbarActive('departments*')}}" href="{{route('main_admin.departments.index')}}">
                <i class="app-menu__icon fa fa-building"></i><span class="app-menu__label">الأدارات</span>
            </a>
        </li>
        @endif

        @if(hasPermission('review_policies') || hasPermission('audit_policies'))
        <li>
            <a class="app-menu__item {{isNavbarActive('policies*')}}" href="{{route('main_admin.policies.index')}}">
                <i class="app-menu__icon fa fa-file"></i><span class="app-menu__label">السياسات</span>
            </a>
        </li>
        @endif

        @if(hasPermission('review_standards') || hasPermission('audit_standards'))
        <li>
            <a class="app-menu__item {{isNavbarActive('standards*')}}" href="{{route('main_admin.standards.index')}}">
                <i class="app-menu__icon fa fa-file"></i><span class="app-menu__label">المعايير</span>
            </a>
        </li>
        @endif
        <li>
            <a class="app-menu__item {{isNavbarActive('chat*')}}" href="{{route('main_admin.chat.index')}}">
                <i class="app-menu__icon fa fa-comments"></i><span class="app-menu__label">التدقيق الاملائي</span>
            </a>
        </li>

{{--        <li class="treeview {{isNavbarTreeActive('reports*')}}">--}}
{{--            <a class="app-menu__item" href="#" data-toggle="treeview">--}}
{{--                <i class="app-menu__icon fa fa-file"></i>--}}
{{--                <span class="app-menu__label">{{__('Reports')}}</span>--}}
{{--                <i class="treeview-indicator fa fa-angle-right"></i>--}}
{{--            </a>--}}
{{--            <ul class="treeview-menu">--}}
{{--                <li><a class="treeview-item {{isFullUrl('reports/worker_performance')}}" href="{{route('main_admin.reports.worker_performance')}}"><i class="icon fa fa-circle-o"></i> {{__('Worker Performance')}}</a></li>--}}
{{--                <li><a class="treeview-item {{isFullUrl('reports/reservation_summary')}}" href="{{route('main_admin.reports.reservation_summary')}}"><i class="icon fa fa-circle-o"></i> {{__('Reservation Summary')}}</a></li>--}}
{{--                <li><a class="treeview-item {{isFullUrl('reports/sales_profit')}}" href="{{route('main_admin.reports.sales_profit')}}"><i class="icon fa fa-circle-o"></i> {{__('Worker Sales Profit')}}</a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}
    </ul>
</aside>
@yield('content')
<div id="scroller"></div>
@include('admin.footer')
</body>
</html>
