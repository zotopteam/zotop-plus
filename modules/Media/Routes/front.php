<?php
use Illuminate\Routing\Router;

// Media 模块前台路由
$router->group(['prefix' =>'media','module'=>'media'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('media.index');
});
