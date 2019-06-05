<?php
use Illuminate\Routing\Router;

// Content 模块前台路由
$router->group(['prefix' =>'content','module'=>'content'], function (Router $router) {
    $router->get('index', 'IndexController@index')->name('content.index');
    $router->get('preview/{id}', 'IndexController@preview')->name('content.preview');
    $router->get('show/{id}', 'IndexController@show')->name('content.show');
    $router->get('search', 'IndexController@search')->name('content.search');
});

// 全局slug路由（slug只允许英文数字和短横杠，排在conent之后的模块，为避免冲突，url需要全都包含斜线）
$router->group(['prefix' =>'/','module'=>'content'], function (Router $router) {
    $router->get('{slug}', 'IndexController@slug')->name('content.slug')->where('slug', '[a-z0-9-]+');
});
