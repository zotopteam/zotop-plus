<?php

use Illuminate\Support\Facades\Route;

// Core 模块前台路由
Route::group(['prefix' => '/'], function () {
    Route::get('/', 'IndexController@index')->name('index');
    Route::get('cms', 'IndexController@index')->name('cms');
});
