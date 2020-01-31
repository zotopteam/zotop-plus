<?php
use Illuminate\Routing\Router;

// Block 模块前台路由
$router->group(['prefix' =>'block'], function (Router $router) {
    
    // 预览
    $router->get('preview/{id}','IndexController@preview')->name('block.preview');

});
