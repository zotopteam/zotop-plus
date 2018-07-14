<?php
use Illuminate\Routing\Router;

// Section 模块前台路由
$router->group(['prefix' =>'section','module'=>'section'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('section.index');
});
