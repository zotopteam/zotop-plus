<?php

use Illuminate\Support\Facades\Route;

// Editormd admin路由
Route::group(['prefix'=>'editormd'], function () {
    
    // 单个路由示例
    //Route::get('/', 'IndexController@index')->name('editormd.index')->middleware('allow:editormd.index');

    // 群组路由示例
    // Route::group(['prefix' =>'example'], function () {
    //    Route::get('index','ExampleController@index')->name('editormd.example.index')->middleware('allow:editormd.example.index');
    //    Route::get('create','ExampleController@create')->name('editormd.example.create')->middleware('allow:editormd.example.create');
    //    Route::post('store','ExampleController@store')->name('editormd.example.store')->middleware('allow:editormd.example.store');    
    //    Route::get('edit/{id}','AdministratorController@edit')->name('editormd.example.edit')->middleware('allow:editormd.example.edit');
    //    Route::put('update/{id}','AdministratorController@update')->name('editormd.example.update')->middleware('allow:editormd.example.update');
    //    Route::delete('destroy/{id}','AdministratorController@destroy')->name('editormd.example.destroy')->middleware('allow:editormd.example.destroy');                 
    // });    
    
});
