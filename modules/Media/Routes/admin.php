<?php

use Illuminate\Routing\Router;

// Media 模块后台路由
$router->group(['prefix' => 'media'], function (Router $router) {
    $router->get('index/{folder_id?}', 'MediaController@index')->name('media.index')->middleware('allow:media.index');
    $router->get('type/{type}', 'MediaController@type')->name('media.type')->middleware('allow:media.index');
    $router->get('show/{id}', 'MediaController@show')->name('media.show')->middleware('allow:media.index');
    $router->any('create/{folder_id}/{type}', 'MediaController@create')->name('media.create')->middleware('allow:media.create');
    $router->any('rename/{id}', 'MediaController@rename')->name('media.rename')->middleware('allow:media.rename');
    $router->any('download/{id}', 'MediaController@download')->name('media.download')->middleware('allow:media.download');
    $router->any('move/{folder_id?}', 'MediaController@move')->name('media.move')->middleware('allow:media.move');
    $router->any('destroy/{id?}', 'MediaController@destroy')->name('media.destroy')->middleware('allow:media.destroy');
    $router->any('select/uploaded', 'MediaController@selectFromUploaded')->name('media.select.uploaded');
    $router->any('select/library/{folder_id?}', 'MediaController@selectFromLibrary')->name('media.select.library');
});
