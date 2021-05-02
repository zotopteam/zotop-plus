<?php

// 只有本地开发环境才加载开发助手的路由
if (!app()->environment('local')) {
    return;
}

use Illuminate\Support\Facades\Route;

// Developer 模块后台路由
Route::group(['prefix' => 'developer'], function () {

    // 首页
    Route::get('index', 'IndexController@index')->name('developer.index');

    // module 开发
    Route::group(['prefix' => '/module', 'middleware' => 'allow:developer.module'], function () {
        Route::get('index', 'ModuleController@index')->name('developer.module.index');
        Route::get('create', 'ModuleController@create')->name('developer.module.create');
        Route::post('store', 'ModuleController@store')->name('developer.module.store');
        Route::get('{module}/show', 'ModuleController@show')->name('developer.module.show');
        Route::post('{module}/update/{field}', 'ModuleController@update')->name('developer.module.update');
    });

    // table group
    Route::group(['prefix' => 'module/{module}/table', 'middleware' => 'allow:developer.table'], function () {
        Route::get('index', 'TableController@index')->name('developer.table.index');
        Route::any('create', 'TableController@create')->name('developer.table.create');
        Route::any('edit/{table}', 'TableController@edit')->name('developer.table.edit');
        Route::delete('drop/{table}', 'TableController@drop')->name('developer.table.drop');
        Route::any('manage/{table}', 'TableController@manage')->name('developer.table.manage');
        Route::any('migration/{table}/{action?}', 'TableController@migration')->name('developer.table.migration');
        Route::any('model/{table}/{force?}', 'TableController@model')->name('developer.table.model');

    });

    // table columns
    Route::any('module/table/columns/{action?}', 'TableController@columns')->name('developer.table.columns');

    // migration group
    Route::group(['prefix' => 'module/{module}/migration', 'middleware' => 'allow:developer.migration'], function () {
        Route::get('index', 'MigrationController@index')->name('developer.migration.index');
        Route::any('create', 'MigrationController@create')->name('developer.migration.create');
        Route::any('execute/{action}', 'MigrationController@execute')->name('developer.migration.execute');
        Route::any('migrate/file/{action}', 'MigrationController@migrateFile')->name('developer.migration.migrate.file');
    });

    // module/controller 开发
    Route::group(['prefix' => 'module/{module}/controller/{type}', 'middleware' => 'allow:developer.controller'], function () {
        Route::get('index', 'ControllerController@index')->name('developer.controller.index');
        Route::any('create', 'ControllerController@create')->name('developer.controller.create');
        Route::any('tempate/{controller}', 'ControllerController@template')->name('developer.controller.template');
        Route::any('route/{controller}', 'ControllerController@route')->name('developer.controller.route');
    });

    // permission scan
    Route::group(['prefix' => 'module/{module}/permission', 'middleware' => 'allow:developer.permission'], function () {
        Route::get('index', 'PermissionController@index')->name('developer.permission.index');
        Route::any('scan', 'PermissionController@scan')->name('developer.permission.scan');
    });

    // command group
    Route::group(['prefix' => 'module/{module}/key/{key}', 'middleware' => 'allow:developer.command'], function () {
        Route::get('index', 'CommandController@index')->name('developer.command.index');
        Route::any('create', 'CommandController@create')->name('developer.command.create');
    });

    // translate
    Route::group(['prefix' => 'module/{module}/translate', 'middleware' => 'allow:developer.translate'], function () {
        Route::get('index', 'TranslateController@index')->name('developer.translate.index');
        Route::post('newfile', 'TranslateController@newfile')->name('developer.translate.newfile');
        Route::post('deletefile', 'TranslateController@deletefile')->name('developer.translate.deletefile');
        Route::get('translate', 'TranslateController@translate')->name('developer.translate.translate');
        Route::post('save', 'TranslateController@save')->name('developer.translate.save');
        Route::post('newkey', 'TranslateController@newkey')->name('developer.translate.newkey');
        Route::post('deletekey', 'TranslateController@deletekey')->name('developer.translate.deletekey');
    });

    // route
    Route::group(['prefix' => 'route', 'middleware' => 'allow:developer.route'], function () {
        Route::get('index', 'RouteController@index')->name('developer.route.index');
    });

    // theme group
    Route::group(['prefix' => 'theme', 'middleware' => 'allow:developer.theme'], function () {
        Route::get('index', 'ThemeController@index')->name('developer.theme.index');
        Route::get('files/{theme?}', 'ThemeController@files')->name('developer.theme.files');
        Route::get('create', 'ThemeController@create')->name('developer.theme.create');
        Route::post('store', 'ThemeController@store')->name('developer.theme.store');
    });

    // form
    Route::group(['prefix' => 'form', 'middleware' => 'allow:developer.form'], function () {
        Route::any('index', 'FormController@index')->name('developer.form.index');
        Route::any('control/{control}', 'FormController@control')->name('developer.form.control');
    });
});
