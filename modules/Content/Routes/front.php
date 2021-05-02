<?php
use Illuminate\Support\Facades\Route;

// Content 模块前台路由
Route::group(['prefix' =>'content'], function () {
    Route::get('index', 'IndexController@index')->name('content.index');
    Route::get('preview/{id}', 'IndexController@preview')->name('content.preview');
    Route::get('show/{id}', 'IndexController@show')->name('content.show');
    Route::get('search', 'IndexController@search')->name('content.search');
});

// 全局slug路由（slug只允许英文数字和短横杠，排在conent之后的模块，为避免冲突，url需要全都包含斜线）
Route::group(['prefix' =>'/'], function () {
    Route::get('{slug}', 'IndexController@slug')->name('content.slug')->where('slug', '[a-z0-9-]+');
});
