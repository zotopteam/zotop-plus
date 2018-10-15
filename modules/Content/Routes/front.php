<?php
use Illuminate\Routing\Router;

// Content 模块前台路由
$router->group(['prefix' =>'content','module'=>'content'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('content.index');
});
