<?php

use Illuminate\Support\Facades\Route;

// Editormd front路由
Route::group(['prefix' =>'editormd'], function () {
    
    // 单个路由示例
    //Route::get('/', 'IndexController@index')->name('editormd.index');

    // 群组路由示例
    // Route::group(['prefix' =>'example'], function () {
    //    Route::get('index','ExampleController@index')->name('editormd.example.index');
    //    Route::get('create','ExampleController@create')->name('editormd.example.create');
    //    Route::post('store','ExampleController@store')->name('editormd.example.store');    
    //    Route::get('edit/{id}','AdministratorController@edit')->name('editormd.example.edit');
    //    Route::put('update/{id}','AdministratorController@update')->name('editormd.example.update');
    //    Route::delete('destroy/{id}','AdministratorController@destroy')->name('editormd.example.destroy');                 
    // }); 
       
});
