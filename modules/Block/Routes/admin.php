<?php
use Illuminate\Routing\Router;

// Block 模块后台路由
$router->group(['prefix'=>'block', 'module'=>'block'], function (Router $router) {
    
    $router->get('index/{category_id?}','BlockController@index')->name('block.index')->middleware('allow:block.index');
    $router->get('create/{category_id}/{type}','BlockController@create')->name('block.create')->middleware('allow:block.create');
    $router->post('store','BlockController@store')->name('block.store')->middleware('allow:block.store');
    $router->get('show/{id}','BlockController@show')->name('block.show')->middleware('allow:block.show');
    $router->get('edit/{id}','BlockController@edit')->name('block.edit')->middleware('allow:block.edit');
    $router->put('update/{id}','BlockController@update')->name('block.update')->middleware('allow:block.update');
    $router->delete('destroy/{id}','BlockController@destroy')->name('block.destroy')->middleware('allow:block.destroy');

    // 区块分类
    $router->group(['prefix' =>'category'], function (Router $router) {
        $router->get('index','CategoryController@index')->name('block.category.index')->middleware('allow:block.category');
        $router->get('create','CategoryController@create')->name('block.category.create')->middleware('allow:block.category');
        $router->post('store','CategoryController@store')->name('block.category.store')->middleware('allow:block.category');
        $router->get('show/{id}','CategoryController@show')->name('block.category.show')->middleware('allow:block.category');
        $router->get('edit/{id}','CategoryController@edit')->name('block.category.edit')->middleware('allow:block.category');
        $router->put('update/{id}','CategoryController@update')->name('block.category.update')->middleware('allow:block.category');
        $router->delete('destroy/{id}','CategoryController@destroy')->name('block.category.destroy')->middleware('allow:block.category');
        $router->post('sort','CategoryController@sort')->name('block.category.sort')->middleware('allow:block.category');
    });  
});


