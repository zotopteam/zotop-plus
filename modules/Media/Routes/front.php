<?php
use Illuminate\Support\Facades\Route;

// Media 模块前台路由
Route::group(['prefix' =>'media'], function () {
    
    // 首页
    Route::get('/', 'IndexController@index');
});
