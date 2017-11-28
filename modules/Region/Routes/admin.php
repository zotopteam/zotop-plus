<?php
use Illuminate\Routing\Router;

// Region 模块后台路由
$router->group(['prefix' =>'/region', 'module'=>'region'], function (Router $router) {
    $router->get('/{parent_id?}', 'IndexController@index')->name('region.index')->middleware('allow:region.index');
    $router->get('/create/{parent_id?}', 'IndexController@create')->name('region.create')->middleware('allow:region.create');
    $router->post('/store', 'IndexController@store')->name('region.store')->middleware('allow:region.store');
    $router->get('/edit/{id}', 'IndexController@edit')->name('region.edit')->middleware('allow:region.edit');
    $router->post('/update/{id}', 'IndexController@update')->name('region.update')->middleware('allow:region.update');
    $router->post('/destroy/{id}', 'IndexController@destroy')->name('region.destroy')->middleware('allow:region.destroy');
    $router->post('/sort', 'IndexController@sort')->name('region.sort')->middleware('allow:region.sort');
    $router->post('/disable/{id}', 'IndexController@disable')->name('region.disable')->middleware('allow:region.state');
    $router->post('/enable/{id}', 'IndexController@enable')->name('region.enable')->middleware('allow:region.state');
});
