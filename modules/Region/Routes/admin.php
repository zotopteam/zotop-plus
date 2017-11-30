<?php
use Illuminate\Routing\Router;

// Region 模块后台路由
$router->group(['prefix' =>'/region', 'module'=>'region'], function (Router $router) {
    $router->get('/{parent_id?}', 'RegionController@index')->name('region.index')->middleware('allow:region.index');
    $router->get('/create/{parent_id?}', 'RegionController@create')->name('region.create')->middleware('allow:region.create');
    $router->post('/store', 'RegionController@store')->name('region.store')->middleware('allow:region.store');
    $router->get('/edit/{id}', 'RegionController@edit')->name('region.edit')->middleware('allow:region.edit');
    $router->post('/update/{id}', 'RegionController@update')->name('region.update')->middleware('allow:region.update');
    $router->post('/destroy/{id}', 'RegionController@destroy')->name('region.destroy')->middleware('allow:region.destroy');
    $router->post('/sort', 'RegionController@sort')->name('region.sort')->middleware('allow:region.sort');
    $router->post('/disable/{id}', 'RegionController@disable')->name('region.disable')->middleware('allow:region.state');
    $router->post('/enable/{id}', 'RegionController@enable')->name('region.enable')->middleware('allow:region.state');
});
