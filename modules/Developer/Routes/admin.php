<?php
use Illuminate\Routing\Router;

// Developer 模块后台路由
$router->group(['prefix' =>'developer','module'=>'developer'], function (Router $router) {
    
    // 首页
    $router->get('index', 'IndexController@index')->name('developer.index');

    // module 开发
    $router->group(['prefix' =>'/module','middleware'=>'allow:developer.module'], function (Router $router) {
        $router->get('index','ModuleController@index')->name('developer.module.index');
        $router->get('create','ModuleController@create')->name('developer.module.create');
        $router->post('store','ModuleController@store')->name('developer.module.store');
        $router->get('{module}/show','ModuleController@show')->name('developer.module.show');
        $router->post('{module}/update/{field}','ModuleController@update')->name('developer.module.update');
    });

    // table group
    $router->group(['prefix' =>'module/{module}/table','middleware'=>'allow:developer.table'], function (Router $router) {
        $router->get('index','TableController@index')->name('developer.table.index');
        $router->any('create','TableController@create')->name('developer.table.create');
        $router->any('edit/{table}','TableController@edit')->name('developer.table.edit');
        $router->delete('drop/{table}','TableController@drop')->name('developer.table.drop');        
        $router->any('manage/{table}','TableController@manage')->name('developer.table.manage');
        $router->any('migration/{table}/{action}','TableController@migration')->name('developer.table.migration');
        $router->any('model/{table}/{action}','TableController@model')->name('developer.table.model');
        
    });

    // table columns
    $router->any('module/table/columns/{action?}','TableController@columns')->name('developer.table.columns');

    // migration group
    $router->group(['prefix' =>'module/{module}/migration','middleware'=>'allow:developer.migration'], function (Router $router) {
        $router->get('index','MigrationController@index')->name('developer.migration.index');
        $router->any('create','MigrationController@create')->name('developer.migration.create');
        $router->any('execute/{action}','MigrationController@execute')->name('developer.migration.execute');
        $router->any('migrate/file/{action}','MigrationController@migrateFile')->name('developer.migration.migrate.file');
    });

    // module/model 开发
    $router->group(['prefix' =>'module/{module}/model','middleware'=>'allow:developer.model'], function (Router $router) {
        $router->get('index','ModelController@index')->name('developer.model.index');
        $router->any('create','ModelController@create')->name('developer.model.create');
    });    

    // module/controller 开发
    $router->group(['prefix' =>'module/{module}/controller/{type}','middleware'=>'allow:developer.controller'], function (Router $router) {
        $router->get('index','ControllerController@index')->name('developer.controller.index');
        $router->any('create','ControllerController@create')->name('developer.controller.create');
        $router->any('tempate/{controller}','ControllerController@template')->name('developer.controller.template');
        $router->any('route/{controller}','ControllerController@route')->name('developer.controller.route');
    });

    // permission scan
    $router->group(['prefix' =>'module/{module}/permission','middleware'=>'allow:developer.permission'], function (Router $router) {
        $router->get('index','PermissionController@index')->name('developer.permission.index');
        $router->any('scan','PermissionController@scan')->name('developer.permission.scan');
    });

    // command group
    $router->group(['prefix' =>'module/{module}/command/{command}','middleware'=>'allow:developer.command'], function (Router $router) {
        $router->get('index','CommandController@index')->name('developer.command.index');
        $router->any('create','CommandController@create')->name('developer.command.create');
    });

    // translate
    $router->group(['prefix' =>'module/{module}/translate','middleware'=>'allow:developer.translate'], function (Router $router) {
        $router->get('index','TranslateController@index')->name('developer.translate.index');
        $router->get('translate','TranslateController@translate')->name('developer.translate.translate');
        $router->post('save','TranslateController@save')->name('developer.translate.save');
    });

    // theme group
    $router->group(['prefix' =>'theme','middleware'=>'allow:developer.permission'], function (Router $router) {
        $router->get('index','ThemeController@index')->name('developer.theme.index');
    });    
});
