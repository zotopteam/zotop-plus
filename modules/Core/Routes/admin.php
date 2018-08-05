<?php
use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'/', 'module'=>'core'], function (Router $router) {

    // 首页
    $router->get('/','IndexController@index')->name('admin.index');

    // 登录
    $router->get('login', 'AuthController@showLoginForm')->name('admin.login');

    // 登录POST
    $router->post('login', 'AuthController@login')->name('admin.login.post');

    // 登出
    $router->any('logout', 'AuthController@logout')->name('admin.logout');

});

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core'], function (Router $router) {

    // 个人管理
    $router->group(['prefix' =>'mine'], function (Router $router) {
       $router->get('edit','MineController@edit')->name('core.mine.edit')->middleware('allow:core.mine.edit');
       $router->put('update','MineController@update')->name('core.mine.update')->middleware('allow:core.mine.edit');
       $router->get('password','MineController@password')->name('core.mine.password')->middleware('allow:core.mine.password');
       $router->put('password_update','MineController@updatePassword')->name('core.mine.password.update')->middleware('allow:core.mine.password');
       $router->get('permission','MineController@permission')->name('core.mine.permission')->middleware('allow:core.mine.permission');
       $router->get('log','MineController@log')->name('core.mine.log')->middleware('allow:core.mine.log');
    });

    // 管理员管理
    $router->group(['prefix' =>'administrator'], function (Router $router) {
       $router->get('index','AdministratorController@index')->name('core.administrator.index')->middleware('allow:core.administrator.index');
       $router->get('create','AdministratorController@create')->name('core.administrator.create')->middleware('allow:core.administrator.create');
       $router->post('store','AdministratorController@store')->name('core.administrator.store')->middleware('allow:core.administrator.create');
       $router->get('edit/{id}','AdministratorController@edit')->name('core.administrator.edit')->middleware('allow:core.administrator.edit');
       $router->put('update/{id}','AdministratorController@update')->name('core.administrator.update')->middleware('allow:core.administrator.edit');
       $router->post('status/{id}','AdministratorController@status')->name('core.administrator.status')->middleware('allow:core.administrator.status');
       $router->delete('destroy/{id}','AdministratorController@destroy')->name('core.administrator.destroy')->middleware('allow:core.administrator.destroy');
    });

    // role
    $router->group(['prefix' =>'role'], function (Router $router) {
        $router->get('index','RoleController@index')->name('core.role.index')->middleware('allow:core.role.index');
        $router->get('create','RoleController@create')->name('core.role.create')->middleware('allow:core.role.create');
        $router->post('store','RoleController@store')->name('core.role.store')->middleware('allow:core.role.create');
        $router->get('edit/{id}','RoleController@edit')->name('core.role.edit')->middleware('allow:core.role.edit');
        $router->put('update/{id}','RoleController@update')->name('core.role.update')->middleware('allow:core.role.edit');
        $router->post('status/{id}','RoleController@status')->name('core.role.status')->middleware('allow:core.role.status');
        $router->delete('destroy/{id}','RoleController@destroy')->name('core.role.destroy')->middleware('allow:core.role.destroy');
    });

    // 系统设置
    $router->group(['prefix' =>'config'], function (Router $router) {
        $router->any('index','ConfigController@index')->name('core.config.index');
        $router->any('upload','ConfigController@upload')->name('core.config.upload')->middleware('allow:core.config.upload');
        $router->any('watermark/test','ConfigController@watermarktest')->name('core.config.watermarktest');
        $router->any('mail','ConfigController@mail')->name('core.config.mail')->middleware('allow:core.config.mail');
        $router->any('mail/test','ConfigController@mailtest')->name('core.config.mailtest')->middleware('allow:core.config.mail');
        $router->any('safe','ConfigController@safe')->name('core.config.safe')->middleware('allow:core.config.safe');
        $router->any('locale','ConfigController@locale')->name('core.config.locale')->middleware('allow:core.config.locale');
    });

    // 系统功能
    $router->group(['prefix' =>'system'], function (Router $router) {
        $router->any('refresh/{mode?}','SystemController@refresh')->name('core.system.refresh');
        $router->get('environment','SystemController@environment')->name('core.system.environment');
        $router->get('about','SystemController@about')->name('core.system.about');
    });

    // 模块管理
    $router->group(['prefix' =>'modules'], function (Router $router) {
        $router->get('index','ModulesController@index')->name('core.modules.index')->middleware('allow:core.modules.index');
        $router->post('enable/{name}','ModulesController@enable')->name('core.modules.enable')->middleware('allow:core.modules.status');
        $router->post('disable/{name}','ModulesController@disable')->name('core.modules.disable')->middleware('allow:core.modules.status');
        $router->post('install/{name}','ModulesController@install')->name('core.modules.install')->middleware('allow:core.modules.install');
        $router->post('uninstall/{name}','ModulesController@uninstall')->name('core.modules.uninstall')->middleware('allow:core.modules.uninstall');
        $router->post('delete/{name}','ModulesController@delete')->name('core.modules.delete')->middleware('allow:core.modules.delete');
    });

    // 主题管理
    $router->group(['prefix' =>'themes'], function (Router $router) {
        $router->get('index/{type?}','ThemesController@index')->name('core.themes.index')->middleware('allow:core.themes.index');
        $router->get('files/{theme?}','ThemesController@files')->name('core.themes.files')->middleware('allow:core.themes.files');
        $router->any('publish/{theme?}','ThemesController@publish')->name('core.themes.publish')->middleware('allow:core.themes.publish');
        $router->any('upload','ThemesController@upload')->name('core.themes.upload')->middleware('allow:core.themes.upload');
    });

    // 文件管理
    $router->group(['prefix' =>'file'], function (Router $router) {
        $router->any('editor','FileController@editor')->name('core.file.editor')->middleware('allow:core.file.editor');
        $router->any('create','FileController@create')->name('core.file.create')->middleware('allow:core.file.create');
        $router->any('delete','FileController@delete')->name('core.file.delete')->middleware('allow:core.file.delete');
        $router->any('copy','FileController@copy')->name('core.file.copy')->middleware('allow:core.file.copy');
        $router->any('rename','FileController@rename')->name('core.file.rename')->middleware('allow:core.file.rename');
        $router->any('upload/{type?}','FileController@upload')->name('core.file.upload')->middleware('allow:core.file.upload');
        $router->any('select','FileController@select')->name('core.file.select')->middleware('allow:core.file.select');
    });

    // 文件夹
    $router->group(['prefix' =>'folder'], function (Router $router) {
        $router->any('create','FolderController@create')->name('core.folder.create')->middleware('allow:core.folder.create');
        $router->any('delete','FolderController@delete')->name('core.folder.delete')->middleware('allow:core.folder.delete');
        $router->any('rename','FolderController@rename')->name('core.folder.rename')->middleware('allow:core.folder.rename');
    });

});
