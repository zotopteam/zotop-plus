<?php

use Illuminate\Support\Facades\Route;

// Content 模块后台路由
Route::group(['prefix' => 'content'], function () {

    Route::get('index/{id?}', 'ContentController@index')->name('content.content.index')->middleware('allow:content.content.index');
    Route::get('create/{id}/{model_id}', 'ContentController@create')->name('content.content.create')->middleware('allow:content.content.create');
    Route::post('store', 'ContentController@store')->name('content.content.store')->middleware('allow:content.content.store');
    Route::get('show/{id}', 'ContentController@show')->name('content.content.show')->middleware('allow:content.content.show');
    Route::get('edit/{id}', 'ContentController@edit')->name('content.content.edit')->middleware('allow:content.content.edit');
    Route::put('update/{id}', 'ContentController@update')->name('content.content.update')->middleware('allow:content.content.update');
    Route::any('destroy/{id?}', 'ContentController@destroy')->name('content.content.destroy')->middleware('allow:content.content.destroy');
    Route::any('status/{status}/{id?}', 'ContentController@status')->name('content.content.status')->middleware('allow:content.content.status');
    Route::any('stick/{id}', 'ContentController@stick')->name('content.content.stick')->middleware('allow:content.content.stick');
    Route::any('sort/{id}', 'ContentController@sort')->name('content.content.sort')->middleware('allow:content.content.sort');
    Route::any('duplicate/{id}', 'ContentController@duplicate')->name('content.content.duplicate')->middleware('allow:content.content.duplicate');
    Route::any('move/{id?}', 'ContentController@move')->name('content.content.move')->middleware('allow:content.content.move');

    // 模型管理
    Route::group(['prefix' => 'model'], function () {
        Route::get('index', 'ModelController@index')->name('content.model.index')->middleware('allow:content.model.index');
        Route::get('create', 'ModelController@create')->name('content.model.create')->middleware('allow:content.model.create');
        Route::post('store', 'ModelController@store')->name('content.model.store')->middleware('allow:content.model.store');
        Route::get('edit/{id}', 'ModelController@edit')->name('content.model.edit')->middleware('allow:content.model.edit');
        Route::put('update/{id}', 'ModelController@update')->name('content.model.update')->middleware('allow:content.model.update');
        Route::delete('destroy/{id}', 'ModelController@destroy')->name('content.model.destroy')->middleware('allow:content.model.destroy');
        Route::any('status/{id}', 'ModelController@status')->name('content.model.status')->middleware('allow:content.model.status');
        Route::any('export/{id}', 'ModelController@export')->name('content.model.export')->middleware('allow:content.model.export');
        Route::any('import', 'ModelController@import')->name('content.model.import')->middleware('allow:content.model.import');
        Route::any('sort', 'ModelController@sort')->name('content.model.sort')->middleware('allow:content.model.sort');
    });

    // 字段管理
    Route::group(['prefix' => 'field'], function () {
        Route::get('index/{model_id}', 'FieldController@index')->name('content.field.index')->middleware('allow:content.field.index');
        Route::any('sort/{model_id}', 'FieldController@sort')->name('content.field.sort')->middleware('allow:content.field.sort');
        Route::get('create/{model_id}', 'FieldController@create')->name('content.field.create')->middleware('allow:content.field.create');
        Route::post('store', 'FieldController@store')->name('content.field.store')->middleware('allow:content.field.store');
        Route::get('edit/{model_id}/{id}', 'FieldController@edit')->name('content.field.edit')->middleware('allow:content.field.edit');
        Route::put('update/{id}', 'FieldController@update')->name('content.field.update')->middleware('allow:content.field.update');
        Route::post('change/{id}', 'FieldController@change')->name('content.field.change')->middleware('allow:content.field.change');
        Route::delete('destroy/{model_id}/{id}', 'FieldController@destroy')->name('content.field.destroy')->middleware('allow:content.field.destroy');
        Route::any('status/{model_id}/{id}', 'FieldController@status')->name('content.field.status')->middleware('allow:content.field.status');
        Route::any('settings/{model_id}', 'FieldController@settings')->name('content.field.settings');
    });
});
