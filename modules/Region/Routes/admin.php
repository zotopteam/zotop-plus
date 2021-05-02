<?php
use Illuminate\Support\Facades\Route;

// Region 模块后台路由
Route::group(['prefix' =>'region'], function () {
    Route::get('/{parent_id?}', 'RegionController@index')->name('region.index')->middleware('allow:region.index');
    Route::get('/create/{parent_id?}', 'RegionController@create')->name('region.create')->middleware('allow:region.create');
    Route::post('/store', 'RegionController@store')->name('region.store')->middleware('allow:region.store');
    Route::get('/edit/{id}', 'RegionController@edit')->name('region.edit')->middleware('allow:region.edit');
    Route::post('/update/{id}', 'RegionController@update')->name('region.update')->middleware('allow:region.update');
    Route::post('/destroy/{id}', 'RegionController@destroy')->name('region.destroy')->middleware('allow:region.destroy');
    Route::post('/sort', 'RegionController@sort')->name('region.sort')->middleware('allow:region.sort');
    Route::post('/disable/{id}', 'RegionController@disable')->name('region.disable')->middleware('allow:region.state');
    Route::post('/enable/{id}', 'RegionController@enable')->name('region.enable')->middleware('allow:region.state');
});
