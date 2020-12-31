<?php

use Illuminate\Routing\Router;

// 接口
Route::group([
    'namespace' => 'Qihucms\Information\Controllers\Api',
    'prefix' => config('qihu.information_prefix', 'information'),
    'middleware' => ['api'],
    'as' => 'api.information.'
], function (Router $router) {
    $router->apiResource('friends', 'FriendController');
    $router->apiResource('messages', 'MessageController');
});

// 后台
Route::group([
    'prefix' => config('admin.route.prefix') . '/information',
    'namespace' => 'Qihucms\Information\Controllers\Admin',
    'middleware' => config('admin.route.middleware'),
    'as' => 'admin.'
], function (Router $router) {
    $router->resource('messages', 'MessageController');
    $router->resource('friends', 'FriendController');
    $router->resource('friend-policies', 'FriendPolicyController');
});