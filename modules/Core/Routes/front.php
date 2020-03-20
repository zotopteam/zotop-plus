<?php
use Illuminate\Routing\Router;

// Core 模块前台路由
$router->group(['prefix' =>'/'], function (Router $router) {
    $router->get('/', 'IndexController@index')->name('index'); 
    $router->get('cms', 'IndexController@index')->name('cms');  
});
