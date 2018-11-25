<?php
use Illuminate\Routing\Router;

// Content 模块前台路由
$router->group(['prefix' =>'content','module'=>'content'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('content.index');

    $router->get('preview/{id}', 'IndexController@preview')->name('content.preview');
    $router->get('show/{id}', 'IndexController@show')->name('content.show');
    $router->get('{slug}', 'IndexController@slug')->name('content.slug')->where('slug', '.*');
});
