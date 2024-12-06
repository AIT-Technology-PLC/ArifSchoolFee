<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;

Route::get('/reports/dashboard', Admin\DashboardController::class)->name('reports.dashboard');

Route::get('/reports/subscriptions', Admin\SubscriptionReportController::class)->name('reports.subscriptions');

Route::get('/reports/users', Admin\UserReportController::class)->name('reports.users');

Route::post('schools/{school}/reset', Admin\CompanyResetAccountController::class)->name('schools.reset');

Route::post('schools/{school}/features', Admin\CompanyFeatureController::class)->name('schools.features.update');

Route::post('schools/{school}/limits', Admin\CompanyLimitController::class)->name('schools.limits.update');

Route::post('schools/{school}/toggle', Admin\CompanyToggleActivationController::class)->name('schools.toggle_activation');

Route::get('schools/{school}/report', Admin\CompanyEngagementReportController::class)->name('schools.report');

Route::post('subscriptions/{subscription}/approve', Admin\ApproveSubscriptionController::class)->name('subscriptions.approve');

Route::post('/features/{feature}/toggle', Admin\ToggleFeatureController::class)->name('features.toggle');

Route::resource('schools', Admin\CompanyController::class);

Route::resource('users', Admin\UserController::class)->except(['show', 'destroy']);

Route::resource('schools.subscriptions', Admin\CompanySubscriptionController::class)->shallow();

Route::resource('limits', Admin\LimitController::class)->only('index');

Route::resource('features', Admin\FeatureController::class)->only('index');

Route::resource('subscriptions', Admin\SubscriptionController::class)->only('index');

Route::resource('plans', Admin\PlanController::class)->except(['destroy']);

Route::resource('schools', Admin\CompanyController::class)->only('index');

Route::resource('school-types', Admin\SchoolTypeController::class)->except(['show']);

Route::resource('login-permissions', Admin\LoginPermissionController::class)->only('index');

Route::resource('roles', Admin\RoleController::class)->except(['show', 'destroy']);

Route::controller(Admin\CompanyPendingController::class)
    ->name('schools.')
    ->prefix('/admin/schools')
    ->group(function () {
        Route::get('/pending', 'pending')->name('pending');
    });

Route::resource('email-settings', Admin\EmailSettingController::class)->only(['create', 'store']);

Route::resource('sms-settings', Admin\SmsSettingController::class)->only(['create', 'store']);

Route::resource('currencies', Admin\CurrencyController::class)->except(['show']);

Route::resource('payment-methods', Admin\PaymentMethodController::class)->except(['show', 'destroy']);

Route::resource('payment-gateways', Admin\PaymentGatewayController::class)->except(['show', 'destroy']);

Route::resource('arifpay-settings', Admin\ArifpaySettingController::class)->only(['create', 'store']);

Route::resource('paypal-settings', Admin\PaypalSettingController::class)->only(['create', 'store']);

//Cache Setting
Route::get('/caches/index', [Admin\CacheController::class, 'index'])->name('caches.index');
Route::get('clear-view-cache', [Admin\CacheController::class, 'clearViewCache'])->name('cache.clearViewCache');
Route::get('clear-route-cache', [Admin\CacheController::class, 'clearRouteCache'])->name('cache.clearRouteCache');
Route::get('clear-config-cache', [Admin\CacheController::class, 'clearConfigCache'])->name('cache.clearConfigCache');
Route::get('clear-all-cache', [Admin\CacheController::class, 'clearCache'])->name('cache.clearCache');
