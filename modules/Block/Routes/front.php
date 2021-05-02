<?php
use Illuminate\Support\Facades\Route;

// Block 模块前台路由
Route::group(['prefix' =>'block'], function () {
    
    // 预览
    Route::get('preview/{id}','IndexController@preview')->name('block.preview');

});
