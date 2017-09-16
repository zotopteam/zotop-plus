<?php
use Illuminate\Routing\Router;

// Test 模块后台路由
$router->group(['prefix' =>'/test','module'=>'test'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('test.index')->middleware('allow:test.index');

    // 单个路由示例
    // $router->get('/test2', 'IndexController@test2')->name('test.test2')->middleware('allow:test.test2');

    // 群组路由示例
    // $router->group(['prefix' =>'/example'], function (Router $router) {
    //    $router->get('/index','ExampleController@index')->name('test.example.index')->middleware('allow:test.example.index');
    //    $router->get('/create','ExampleController@create')->name('test.example.create')->middleware('allow:test.example.create');
    //    $router->post('/store','ExampleController@store')->name('test.example.store')->middleware('allow:test.example.store');    
    //    $router->get('/edit/{id}','AdministratorController@edit')->name('test.example.edit')->middleware('allow:test.example.edit');
    //    $router->put('/update/{id}','AdministratorController@update')->name('test.example.update')->middleware('allow:test.example.update');
    //    $router->delete('/destroy/{id}','AdministratorController@destroy')->name('test.example.destroy')->middleware('allow:test.example.destroy');                 
    // });    
    
});
