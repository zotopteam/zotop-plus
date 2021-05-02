<?php

use Illuminate\Support\Facades\Route;

// Media 模块后台路由
Route::group(['prefix' => 'media'], function () {
    Route::get('index/{folder_id?}', 'MediaController@index')->name('media.index')->middleware('allow:media.index');
    Route::get('type/{type}', 'MediaController@type')->name('media.type')->middleware('allow:media.index');
    Route::get('show/{id}', 'MediaController@show')->name('media.show')->middleware('allow:media.index');
    Route::any('create/{folder_id}/{type}', 'MediaController@create')->name('media.create')->middleware('allow:media.create');
    Route::any('rename/{id}', 'MediaController@rename')->name('media.rename')->middleware('allow:media.rename');
    Route::any('download/{id}', 'MediaController@download')->name('media.download')->middleware('allow:media.download');
    Route::any('move/{folder_id?}', 'MediaController@move')->name('media.move')->middleware('allow:media.move');
    Route::any('destroy/{id?}', 'MediaController@destroy')->name('media.destroy')->middleware('allow:media.destroy');
    Route::any('select/uploaded', 'MediaController@selectFromUploaded')->name('media.select.uploaded');
    Route::any('select/library/{folder_id?}', 'MediaController@selectFromLibrary')->name('media.select.library');
});
