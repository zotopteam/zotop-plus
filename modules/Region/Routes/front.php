<?php
use Illuminate\Routing\Router;

// Region 模块前台路由
$router->group(['prefix' =>'/region','module'=>'region'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('region.index');
});
