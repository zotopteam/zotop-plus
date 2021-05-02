<?php
use Illuminate\Support\Facades\Route;

// Developer 模块前台路由
Route::group(['prefix' =>'developer'], function () {
    
    // 首页
    Route::get('/', 'IndexController@index');
});
