<?php
use Illuminate\Routing\Router;

// Block 模块后台路由
$router->group(['prefix'=>'block'], function (Router $router) {
    
    $router->get('index/{category_id?}','BlockController@index')->name('block.index')->middleware('allow:block.index');
    $router->get('create/{category_id}/{type}','BlockController@create')->name('block.create')->middleware('allow:block.create');
    $router->post('store','BlockController@store')->name('block.store')->middleware('allow:block.create');
    $router->get('edit/{id}','BlockController@edit')->name('block.edit')->middleware('allow:block.edit');
    $router->put('update/{id}','BlockController@update')->name('block.update')->middleware('allow:block.edit');
    $router->any('data/{id}','BlockController@data')->name('block.data')->middleware('allow:block.data');
    $router->delete('destroy/{id}','BlockController@destroy')->name('block.destroy')->middleware('allow:block.destroy');
    $router->post('sort','BlockController@sort')->name('block.sort')->middleware('allow:block.sort');
    $router->any('fields/{action?}','BlockController@fields')->name('block.fields');

    // 区块分类
    $router->group(['prefix' =>'category'], function (Router $router) {
        $router->get('index','CategoryController@index')->name('block.category.index')->middleware('allow:block.category');
        $router->get('create','CategoryController@create')->name('block.category.create')->middleware('allow:block.category');
        $router->post('store','CategoryController@store')->name('block.category.store')->middleware('allow:block.category');
        $router->get('edit/{id}','CategoryController@edit')->name('block.category.edit')->middleware('allow:block.category');
        $router->put('update/{id}','CategoryController@update')->name('block.category.update')->middleware('allow:block.category');
        $router->delete('destroy/{id}','CategoryController@destroy')->name('block.category.destroy')->middleware('allow:block.category');
        $router->post('sort','CategoryController@sort')->name('block.category.sort')->middleware('allow:block.category');
    });

    // datalist group example
    $router->group(['prefix' =>'datalist'], function (Router $router) {
        $router->get('index/{block_id}','DatalistController@index')->name('block.datalist.index')->middleware('allow:block.data');
        $router->get('history/{block_id}','DatalistController@history')->name('block.datalist.history')->middleware('allow:block.data');
        $router->get('create/{block_id}','DatalistController@create')->name('block.datalist.create')->middleware('allow:block.data');
        $router->post('store','DatalistController@store')->name('block.datalist.store')->middleware('allow:block.data');
        $router->get('edit/{id}','DatalistController@edit')->name('block.datalist.edit')->middleware('allow:block.data');
        $router->put('update/{id}','DatalistController@update')->name('block.datalist.update')->middleware('allow:block.data');
        $router->delete('destroy/{id}','DatalistController@destroy')->name('block.datalist.destroy')->middleware('allow:block.data');
        $router->post('sort/{block_id}','DatalistController@sort')->name('block.datalist.sort')->middleware('allow:block.data');
        $router->post('stick/{id}/{stick}','DatalistController@stick')->name('block.datalist.stick')->middleware('allow:block.data');
        $router->post('republish/{id}','DatalistController@republish')->name('block.datalist.republish')->middleware('allow:block.data');
    });     
});


