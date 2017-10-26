<?php
use Illuminate\Routing\Router;

// Test2 模块后台路由
$router->group(['prefix' =>'test2','module'=>'test2'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('test2.index')->middleware('allow:test2.index');

    // 单个路由示例
    // $router->get('test2', 'IndexController@test2')->name('test2.test2')->middleware('allow:test2.test2');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('test2.example.index')->middleware('allow:test2.example.index');
    //    $router->get('create','ExampleController@create')->name('test2.example.create')->middleware('allow:test2.example.create');
    //    $router->post('store','ExampleController@store')->name('test2.example.store')->middleware('allow:test2.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('test2.example.edit')->middleware('allow:test2.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('test2.example.update')->middleware('allow:test2.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('test2.example.destroy')->middleware('allow:test2.example.destroy');                 
    // });    
    
});
