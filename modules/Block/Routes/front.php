<?php
use Illuminate\Routing\Router;

// Block 模块前台路由
$router->group(['prefix' =>'block','module'=>'block'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('block.index');
});
