<?php

use Illuminate\Routing\Router;

// Site 模块前台路由
$router->group(['prefix' => '/'], function (Router $router) {

    // 首页
    $router->get('/', 'SiteController@index')->name('index');
});

