<?php

use Illuminate\Support\Facades\Route;

// Core 模块后台路由
Route::group(['prefix' => '/'], function () {

    // 首页
    Route::get('/', 'IndexController@index')->name('admin.index');

    // 登录
    Route::any('login', 'AuthController@login')->name('admin.login')->withoutMiddleware('admin');

    // 登出
    Route::any('logout', 'AuthController@logout')->name('admin.logout');
});

// Core 模块后台路由
Route::group(['prefix' => 'core'], function () {

    // 个人管理
    Route::group(['prefix' => 'mine'], function () {
        Route::get('edit', 'MineController@edit')->name('core.mine.edit')->middleware('allow:core.mine.edit');
        Route::put('update', 'MineController@update')->name('core.mine.update')->middleware('allow:core.mine.edit');
        Route::get('password', 'MineController@password')->name('core.mine.password')->middleware('allow:core.mine.password');
        Route::put('password_update', 'MineController@updatePassword')->name('core.mine.password.update')->middleware('allow:core.mine.password');
        Route::get('permission', 'MineController@permission')->name('core.mine.permission')->middleware('allow:core.mine.permission');
        Route::get('log', 'MineController@log')->name('core.mine.log')->middleware('allow:core.mine.log');
    });

    // 管理员管理
    Route::group(['prefix' => 'administrator'], function () {
        Route::get('index', 'AdministratorController@index')->name('core.administrator.index')->middleware('allow:core.administrator.index');
        Route::get('create', 'AdministratorController@create')->name('core.administrator.create')->middleware('allow:core.administrator.create');
        Route::post('store', 'AdministratorController@store')->name('core.administrator.store')->middleware('allow:core.administrator.create');
        Route::get('edit/{id}', 'AdministratorController@edit')->name('core.administrator.edit')->middleware('allow:core.administrator.edit');
        Route::put('update/{id}', 'AdministratorController@update')->name('core.administrator.update')->middleware('allow:core.administrator.edit');
        Route::post('status/{id}', 'AdministratorController@status')->name('core.administrator.status')->middleware('allow:core.administrator.status');
        Route::delete('destroy/{id}', 'AdministratorController@destroy')->name('core.administrator.destroy')->middleware('allow:core.administrator.destroy');
    });

    // role
    Route::group(['prefix' => 'role'], function () {
        Route::get('index', 'RoleController@index')->name('core.role.index')->middleware('allow:core.role.index');
        Route::get('create', 'RoleController@create')->name('core.role.create')->middleware('allow:core.role.create');
        Route::post('store', 'RoleController@store')->name('core.role.store')->middleware('allow:core.role.create');
        Route::get('edit/{id}', 'RoleController@edit')->name('core.role.edit')->middleware('allow:core.role.edit');
        Route::put('update/{id}', 'RoleController@update')->name('core.role.update')->middleware('allow:core.role.edit');
        Route::post('status/{id}', 'RoleController@status')->name('core.role.status')->middleware('allow:core.role.status');
        Route::delete('destroy/{id}', 'RoleController@destroy')->name('core.role.destroy')->middleware('allow:core.role.destroy');
    });

    // 系统设置
    Route::group(['prefix' => 'config'], function () {
        Route::any('index', 'ConfigController@index')->name('core.config.index');
        Route::any('upload', 'ConfigController@upload')->name('core.config.upload')->middleware('allow:core.config.upload');
        Route::any('watermark/test', 'ConfigController@watermarkTest')->name('core.config.watermarktest');
        Route::any('mail', 'ConfigController@mail')->name('core.config.mail')->middleware('allow:core.config.mail');
        Route::any('mail/test', 'ConfigController@mailTest')->name('core.config.mailtest')->middleware('allow:core.config.mail');
        Route::any('safe', 'ConfigController@safe')->name('core.config.safe')->middleware('allow:core.config.safe');
        Route::any('locale', 'ConfigController@locale')->name('core.config.locale')->middleware('allow:core.config.locale');
    });

    // 系统功能
    Route::group(['prefix' => 'system'], function () {
        Route::any('manage', 'SystemController@manage')->name('core.system.manage')->middleware('allow:core.system.manage');
        Route::any('size', 'SystemController@size')->name('core.system.size');
        Route::get('environment', 'SystemController@environment')->name('core.system.environment')->middleware('allow:core.system.environment');
        Route::get('about', 'SystemController@about')->name('core.system.about');
    });

    // 模块管理
    Route::group(['prefix' => 'module'], function () {
        Route::get('index', 'ModuleController@index')->name('core.module.index')->middleware('allow:core.module.index');
        Route::post('enable/{module}', 'ModuleController@enable')->name('core.module.enable')->middleware('allow:core.module.status');
        Route::post('disable/{module}', 'ModuleController@disable')->name('core.module.disable')->middleware('allow:core.module.status');
        Route::post('install/{module}', 'ModuleController@install')->name('core.module.install')->middleware('allow:core.module.install');
        Route::post('upgrade/{module}', 'ModuleController@upgrade')->name('core.module.upgrade')->middleware('allow:core.module.upgrade');
        Route::post('uninstall/{module}', 'ModuleController@uninstall')->name('core.module.uninstall')->middleware('allow:core.module.uninstall');
        Route::post('delete/{module}', 'ModuleController@delete')->name('core.module.delete')->middleware('allow:core.module.delete');
        Route::any('publish/{module?}', 'ModuleController@publish')->name('core.module.publish')->middleware('allow:core.module.publish');
        Route::any('upload', 'ModuleController@upload')->name('core.module.upload')->middleware('allow:core.module.upload');
    });

    // 主题管理
    Route::group(['prefix' => 'theme'], function () {
        Route::get('index/{type?}', 'ThemeController@index')->name('core.theme.index')->middleware('allow:core.theme.index');
        Route::any('publish/{theme?}', 'ThemeController@publish')->name('core.theme.publish')->middleware('allow:core.theme.publish');
        Route::any('upload', 'ThemeController@upload')->name('core.theme.upload')->middleware('allow:core.theme.upload');
        Route::any('delete/{theme}', 'ThemeController@delete')->name('core.theme.delete')->middleware('allow:core.theme.delete');
    });

    // storage 存储管理
    Route::group(['prefix' => 'storage/{disk}'], function () {
        Route::get('index', 'StorageController@index')->name('core.storage.index')->middleware('allow:core.storage.index');
        Route::post('folder/create', 'StorageController@folderCreate')->name('core.storage.folder.create')->middleware('allow:core.storage.folder.create');
        Route::post('folder/rename', 'StorageController@folderRename')->name('core.storage.folder.rename')->middleware('allow:core.storage.folder.rename');
        Route::delete('folder/delete', 'StorageController@folderDelete')->name('core.storage.folder.delete')->middleware('allow:core.storage.folder.delete');
        Route::post('file/rename', 'StorageController@fileRename')->name('core.storage.file.rename')->middleware('allow:core.storage.file.rename');
        Route::delete('file/delete', 'StorageController@fileDelete')->name('core.storage.file.delete')->middleware('allow:core.storage.file.delete');
        Route::get('file/download', 'StorageController@fileDownload')->name('core.storage.file.download')->middleware('allow:core.storage.file.download');
        Route::get('file/select', 'StorageController@fileSelect')->name('core.storage.file.select');
    });

    // 文件管理
    Route::group(['prefix' => 'file'], function () {
        Route::any('editor', 'FileController@editor')->name('core.file.editor')->middleware('allow:core.file.editor');
        Route::any('create', 'FileController@create')->name('core.file.create')->middleware('allow:core.file.create');
        Route::any('delete', 'FileController@delete')->name('core.file.delete')->middleware('allow:core.file.delete');
        Route::any('copy', 'FileController@copy')->name('core.file.copy')->middleware('allow:core.file.copy');
        Route::any('rename', 'FileController@rename')->name('core.file.rename')->middleware('allow:core.file.rename');
        Route::any('upload_chunk', 'FileController@uploadChunk')->name('core.file.upload_chunk');
        Route::any('upload', 'FileController@upload')->name('core.file.upload');
        Route::any('select', 'FileController@select')->name('core.file.select');
    });

    // 文件夹
    Route::group(['prefix' => 'folder'], function () {
        Route::any('create', 'FolderController@create')->name('core.folder.create')->middleware('allow:core.folder.create');
        Route::any('delete', 'FolderController@delete')->name('core.folder.delete')->middleware('allow:core.folder.delete');
        Route::any('rename', 'FolderController@rename')->name('core.folder.rename')->middleware('allow:core.folder.rename');
    });

    // scheduling group example
    Route::group(['prefix' => 'scheduling'], function () {
        Route::get('index', 'SchedulingController@index')->name('core.scheduling.index')->middleware('allow:core.scheduling.index');
        Route::any('run/{index}', 'SchedulingController@run')->name('core.scheduling.run')->middleware('allow:core.scheduling.run');
    });

    // notifications
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('index', 'NotificationsController@index')->name('core.notifications.index');
        Route::any('check', 'NotificationsController@check')->name('core.notifications.check');
    });

    // log
    Route::group(['prefix' => 'log'], function () {
        Route::get('index', 'LogController@index')->name('core.log.index')->middleware('allow:core.log.index');
    });
});
