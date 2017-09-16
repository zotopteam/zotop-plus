<?php
use Illuminate\Routing\Router;

// Test 模块前台路由
$router->group(['prefix' =>'/test','module'=>'test'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('test.index');
});
