<?php
use Illuminate\Support\Facades\Route;

// Translator 模块后台路由
Route::group(['prefix'=>'translator'], function () {
    
    // 首页
    Route::any('/translate', 'TranslatorController@translate')->name('translator.translate');

    // config group example
    Route::group(['prefix' =>'config'], function () {
        Route::any('index','ConfigController@index')->name('translator.config.index')->middleware('allow:translator.config');
    });
    
});
