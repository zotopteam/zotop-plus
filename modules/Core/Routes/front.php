<?php
use Illuminate\Routing\Router;

// Core 模块前台路由
$router->group(['prefix' =>'/','module'=>'core'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('index');    
});
