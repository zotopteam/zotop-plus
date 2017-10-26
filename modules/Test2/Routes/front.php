<?php
use Illuminate\Routing\Router;

// Test2 模块前台路由
$router->group(['prefix' =>'test2','module'=>'test2'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('test2.index');
});
