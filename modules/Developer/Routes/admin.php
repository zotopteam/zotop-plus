<?php
use Illuminate\Routing\Router;

// Developer 模块后台路由
$router->group(['prefix' =>'developer','module'=>'developer'], function (Router $router) {
    
    // 首页
    $router->get('index', 'IndexController@index')->name('developer.index')->middleware('allow:developer.index');

    // module 开发
    $router->group(['prefix' =>'/module','middleware'=>'allow:developer.module'], function (Router $router) {
        $router->get('index','ModuleController@index')->name('developer.module.index');
        $router->get('create','ModuleController@create')->name('developer.module.create');
        $router->post('store','ModuleController@store')->name('developer.module.store');
        $router->get('show/{name}','ModuleController@show')->name('developer.module.show');
        $router->post('update/{name}/{field}','ModuleController@update')->name('developer.module.update');
    });

    // module/controller 开发
    $router->group(['prefix' =>'module/controller','middleware'=>'allow:developer.module.controller'], function (Router $router) {
        $router->get('index/{name}/{type}','ControllerController@index')->name('developer.module.controller');
        $router->any('create/{name}/{type}','ControllerController@create')->name('developer.module.controller.create');
        $router->any('tempate/{name}/{type}/{controller}','ControllerController@template')->name('developer.module.controller.template');
        $router->any('route/{name}/{type}/{controller}','ControllerController@route')->name('developer.module.controller.route');
    });    
 
    
    // theme 开发
    $router->group(['prefix' =>'theme','middleware'=>'allow:developer.theme'], function (Router $router) {
        $router->get('index','themeController@index')->name('developer.theme.index');
        $router->get('create','themeController@create')->name('developer.theme.create');
        $router->post('store','themeController@store')->name('developer.theme.store');  
        $router->get('edit/{name}','themeController@destroy@edit')->name('developer.theme.edit');
        $router->put('update/{name}','themeController@update')->name('developer.theme.update');
        $router->delete('destroy/{name}','themeController@destroy')->name('developer.theme.destroy');                 
    });
    
});
