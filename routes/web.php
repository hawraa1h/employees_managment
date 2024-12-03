<?php

use App\Http\Controllers\Admin\ContactUsController as ADMIN_ContactUsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\MainAdminController;
use App\Http\Controllers\Admin\StandardController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Admin\CustomerController as ADMIN_CustomerController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\WorkerController as ADMIN_WorkerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::view('generate-strong-password', 'generate_strong_paswword')->name('generate_strong_paswword');

Route::get('/', [MainAdminController::class, 'getFormLogin'])->name('index');



Route::name('main_admin.')->group(function () {
    Route::get('/login', [MainAdminController::class, 'getFormLogin'])->name('login');
    Route::post('/login', [MainAdminController::class, 'postLogin'])->name('login');
    Route::get('/password-update', [MainAdminController::class, 'showPasswordUpdateForm'])->name('password_update');
    Route::post('/password-update', [MainAdminController::class, 'updatePassword'])->name('password_update.submit');
    Route::middleware('auth')->group(function () {
        Route::get('home', [MainAdminController::class, 'home'])->name('home');
        Route::post('logout', [MainAdminController::class, 'logout'])->name('logout');
        Route::get('my_profile', [MainAdminController::class, 'getMyProfile'])->name('my_profile');
        Route::post('my_profile', [MainAdminController::class, 'updateMyProfile'])->name('my_profile');
        Route::get('settings', [App\Http\Controllers\Admin\MainAdminController::class, 'getSettings'])->name('settings');
        Route::post('settings/update', [App\Http\Controllers\Admin\MainAdminController::class, 'updateSettings'])->name('settings_update');
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('employees', EmployeeController::class);

        Route::post('policies/{policy}/update-notes', [PolicyController::class, 'updateNotes'])->name('policies.updateNotes');
        Route::post('policies/{policy}/audit', [PolicyController::class, 'audit'])->name('policies.audit');
        Route::post('policies/{policy}/review', [PolicyController::class, 'review'])->name('policies.review');
        Route::resource('policies', PolicyController::class);

        Route::resource('standards', StandardController::class);
        Route::post('standards/{policy}/audit', [StandardController::class, 'audit'])->name('standards.audit');
        Route::post('standards/{policy}/review', [StandardController::class, 'review'])->name('standards.review');

        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('chat/send', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');

        Route::get('reports/worker_performance', [ReportController::class, 'workerPerformanceReport'])->name('reports.worker_performance');
        Route::get('reports/reservation_summary', [ReportController::class, 'reservationSummaryReport'])->name('reports.reservation_summary');
        Route::get('reports/sales_profit', [ReportController::class, 'workerSalesProfitReport'])->name('reports.sales_profit');


        Route::post('policies/{policy}/check', [PolicyController::class, 'updateCheckedStatus'])->name('policies.updateCheckedStatus');

    });
});
