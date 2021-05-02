<?php

use Illuminate\Support\Facades\Route;

// Block 模块后台路由
Route::group(['prefix' => 'block'], function () {

    Route::get('index/{category_id?}', 'BlockController@index')->name('block.index')->middleware('allow:block.index');
    Route::get('create/{category_id}/{type}', 'BlockController@create')->name('block.create')->middleware('allow:block.create');
    Route::post('store', 'BlockController@store')->name('block.store')->middleware('allow:block.create');
    Route::get('edit/{id}', 'BlockController@edit')->name('block.edit')->middleware('allow:block.edit');
    Route::put('update/{id}', 'BlockController@update')->name('block.update')->middleware('allow:block.edit');
    Route::any('data/{id}', 'BlockController@data')->name('block.data')->middleware('allow:block.data');
    Route::delete('destroy/{id}', 'BlockController@destroy')->name('block.destroy')->middleware('allow:block.destroy');
    Route::post('sort', 'BlockController@sort')->name('block.sort')->middleware('allow:block.sort');
    Route::any('fields/{action?}', 'BlockController@fields')->name('block.fields');

    // 区块分类
    Route::group(['prefix' => 'category'], function () {
        Route::get('index', 'CategoryController@index')->name('block.category.index')->middleware('allow:block.category');
        Route::get('create', 'CategoryController@create')->name('block.category.create')->middleware('allow:block.category');
        Route::post('store', 'CategoryController@store')->name('block.category.store')->middleware('allow:block.category');
        Route::get('edit/{id}', 'CategoryController@edit')->name('block.category.edit')->middleware('allow:block.category');
        Route::put('update/{id}', 'CategoryController@update')->name('block.category.update')->middleware('allow:block.category');
        Route::delete('destroy/{id}', 'CategoryController@destroy')->name('block.category.destroy')->middleware('allow:block.category');
        Route::post('sort', 'CategoryController@sort')->name('block.category.sort')->middleware('allow:block.category');
    });

    // datalist group example
    Route::group(['prefix' => 'datalist'], function () {
        Route::get('index/{block_id}', 'DatalistController@index')->name('block.datalist.index')->middleware('allow:block.data');
        Route::get('history/{block_id}', 'DatalistController@history')->name('block.datalist.history')->middleware('allow:block.data');
        Route::get('create/{block_id}', 'DatalistController@create')->name('block.datalist.create')->middleware('allow:block.data');
        Route::post('store', 'DatalistController@store')->name('block.datalist.store')->middleware('allow:block.data');
        Route::get('edit/{id}', 'DatalistController@edit')->name('block.datalist.edit')->middleware('allow:block.data');
        Route::put('update/{id}', 'DatalistController@update')->name('block.datalist.update')->middleware('allow:block.data');
        Route::delete('destroy/{id}', 'DatalistController@destroy')->name('block.datalist.destroy')->middleware('allow:block.data');
        Route::post('sort/{block_id}', 'DatalistController@sort')->name('block.datalist.sort')->middleware('allow:block.data');
        Route::post('stick/{id}/{stick}', 'DatalistController@stick')->name('block.datalist.stick')->middleware('allow:block.data');
        Route::post('republish/{id}', 'DatalistController@republish')->name('block.datalist.republish')->middleware('allow:block.data');
    });
});


