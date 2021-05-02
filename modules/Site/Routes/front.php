<?php

use Illuminate\Support\Facades\Route;

// Site 模块前台路由
Route::group(['prefix' => '/'], function () {

    // 首页
    Route::get('/', 'SiteController@index')->name('index');
});

