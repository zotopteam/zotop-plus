<?php
use Illuminate\Support\Facades\Route;

// Site 模块后台路由
Route::group(['prefix' =>'site'], function () {
    
    // 模板选择
    Route::get('select/view/{theme?}','SiteController@selectView')->name('site.view.select');


    // 系统设置
    Route::group(['prefix' =>'config'], function () {
        Route::any('base','ConfigController@base')->name('site.config.base')->middleware('allow:site.config.base');
        Route::any('wap','ConfigController@wap')->name('site.config.wap')->middleware('allow:site.config.wap');
        Route::any('seo','ConfigController@seo')->name('site.config.seo')->middleware('allow:site.config.seo');
        Route::any('maintain','ConfigController@maintain')->name('site.config.maintain')->middleware('allow:site.config.maintain');
    });    
});
