<?php

use App\Admin\Controllers\HomeController;
use App\Admin\Controllers\SettingController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Slowlyo\OwlAdmin\Admin;

Route::get('/admin', fn () => Admin::view());

Route::group([
    'domain' => config('admin.route.domain'),
    'prefix' => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->resource('dashboard', HomeController::class);
    $router->resource('system/settings', SettingController::class);
});
