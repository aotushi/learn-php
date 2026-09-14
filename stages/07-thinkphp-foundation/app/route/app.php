<?php

use think\facade\Route;
use think\facade\Db;
use app\middleware\RequireLogin;

Route::get('api/health', function () {
    Db::query('SELECT 1');
    return json([
        'success' => true,
        'data' => [
            'service' => 'thinkphp-admin-api',
            'framework' => app()->version(),
        ],
    ]);
});

Route::post('api/auth/login', 'Auth/login');
Route::get('api/auth/profile', 'Auth/profile');
Route::post('api/auth/logout', 'Auth/logout');

Route::group('api/products', function () {
    Route::get('list', 'Product/index');
    Route::post('create', 'Product/create');
    Route::get(':id/urls', 'Product/urls');
    Route::post(':id/urls', 'Product/addUrls');
    Route::delete(':productId/urls/:urlId', 'Product/deleteUrl');
    Route::put(':id', 'Product/update');
    Route::delete(':id', 'Product/delete');
})
    ->pattern([
        'id' => '\d+',
        'productId' => '\d+',
        'urlId' => '\d+',
    ])
    ->completeMatch()
    ->middleware(RequireLogin::class);
