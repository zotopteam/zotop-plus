<?php
use Illuminate\Routing\Router;

// Site 模块前台路由
$router->group(['prefix' =>'/','module'=>'site'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('index');
});


// Site 模块前台路由
$router->group(['prefix' =>'/site','module'=>'site'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('site.index');
});
