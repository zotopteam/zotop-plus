<?php
use Illuminate\Routing\Router;

// Media 模块后台路由
$router->group(['prefix'=>'media', 'module'=>'media'], function (Router $router) {
    $router->get('index/{folder_id?}/{type?}', 'MediaController@index')->name('media.index');
    $router->any('select/uploaded','MediaController@selectFromUploaded')->name('media.select.uploaded');
    $router->any('select/library/{folder?}','MediaController@selectFromLibrary')->name('media.select.library');
    $router->any('select/dir','MediaController@selectFromDir')->name('media.select.dir');
    $router->any('create/{parent_id}/{type}','MediaController@create')->name('media.create')->middleware('allow:media.create');
    $router->any('rename/{id}','MediaController@rename')->name('media.rename')->middleware('allow:media.rename');
    $router->any('move/{parent_id?}','MediaController@move')->name('media.move')->middleware('allow:media.move');
    $router->any('destroy/{id?}','MediaController@destroy')->name('media.destroy')->middleware('allow:media.destroy');
});
