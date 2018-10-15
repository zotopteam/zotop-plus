<?php
use Illuminate\Routing\Router;

// Content 模块后台路由
$router->group(['prefix'=>'content', 'module'=>'content'], function (Router $router) {
    
    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('content.index')->middleware('allow:content.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('content.example.index')->middleware('allow:content.example.index');
    //    $router->get('create','ExampleController@create')->name('content.example.create')->middleware('allow:content.example.create');
    //    $router->post('store','ExampleController@store')->name('content.example.store')->middleware('allow:content.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('content.example.edit')->middleware('allow:content.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('content.example.update')->middleware('allow:content.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('content.example.destroy')->middleware('allow:content.example.destroy');                 
    // });    
    
});
