<?php
use Illuminate\Routing\Router;

// Developer 模块前台路由
$router->group(['prefix' =>'developer','module'=>'developer'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('developer.index');
});
