<?php
use Illuminate\Routing\Router;

// Translator 模块后台路由
$router->group(['prefix'=>'translator', 'module'=>'translator'], function (Router $router) {
    
    // 首页
    $router->any('/translate', 'TranslatorController@translate')->name('translator.translate');

    // config group example
    $router->group(['prefix' =>'config'], function (Router $router) {
        $router->any('index','ConfigController@index')->name('translator.config.index')->middleware('allow:translator.config');
    });
    
});
