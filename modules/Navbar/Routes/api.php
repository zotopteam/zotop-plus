<?php

use Illuminate\Support\Facades\Route;

// Navbar api路由
Route::group(['prefix' =>'navbar'], function () {
    
    // 单个路由示例
    //Route::get('/', 'IndexController@index')->name('navbar.index');

    // 群组路由示例
    // Route::group(['prefix' =>'example'], function () {
    //    Route::get('index','ExampleController@index')->name('navbar.example.index');
    //    Route::get('create','ExampleController@create')->name('navbar.example.create');
    //    Route::post('store','ExampleController@store')->name('navbar.example.store');    
    //    Route::get('edit/{id}','AdministratorController@edit')->name('navbar.example.edit');
    //    Route::put('update/{id}','AdministratorController@update')->name('navbar.example.update');
    //    Route::delete('destroy/{id}','AdministratorController@destroy')->name('navbar.example.destroy');                 
    // }); 
       
});
