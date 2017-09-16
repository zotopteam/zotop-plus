<?php
use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'/', 'module'=>'core'], function (Router $router) {

    // 首页
    $router->get('/','IndexController@index')->name('admin.index')->middleware('allow:admin.index');

    // 登录
    $router->get('/login', 'AuthController@showLoginForm')->name('admin.login');

    // 登录POST
    $router->post('/login', 'AuthController@login')->name('admin.login.post');

    // 登出
    $router->any('/logout', 'AuthController@logout')->name('admin.logout');
    
});

// Core 模块后台路由
$router->group(['prefix' =>'/core', 'module'=>'core'], function (Router $router) {

    // 个人管理
    $router->group(['prefix' =>'/mine'], function (Router $router) {       
       $router->get('/edit','MineController@edit')->name('core.mine.edit')->middleware('allow:core.mine.edit');
       $router->put('/update','MineController@update')->name('core.mine.update')->middleware('allow:core.mine.update');
       $router->get('/password','MineController@password')->name('core.mine.password')->middleware('allow:core.mine.password');
       $router->put('/password_update','MineController@updatePassword')->name('core.mine.password.update')->middleware('allow:core.mine.password');       
       $router->get('/permission','MineController@permission')->name('core.mine.permission')->middleware('allow:core.mine.permission');
       $router->get('/log','MineController@log')->name('core.mine.log')->middleware('allow:core.mine.log');
    });

    // 管理员管理
    $router->group(['prefix' =>'/administrator'], function (Router $router) {
       $router->get('/index','AdministratorController@index')->name('core.administrator.index')->middleware('allow:core.administrator.index');
       $router->get('/create','AdministratorController@create')->name('core.administrator.create')->middleware('allow:core.administrator.create');
       $router->post('/store','AdministratorController@store')->name('core.administrator.store')->middleware('allow:core.administrator.store');
       $router->get('/edit/{id}','AdministratorController@edit')->name('core.administrator.edit')->middleware('allow:core.administrator.edit');
       $router->put('/update/{id}','AdministratorController@update')->name('core.administrator.update')->middleware('allow:core.administrator.update');
       $router->post('/status/{id}','AdministratorController@status')->name('core.administrator.status')->middleware('allow:core.administrator.status');
       $router->delete('/destroy/{id}','AdministratorController@destroy')->name('core.administrator.destroy')->middleware('allow:core.administrator.destroy');           
    });

    // 系统设置
    $router->group(['prefix' =>'/config'], function (Router $router) {
        $router->any('/core/upload','ConfigController@upload')->name('core.config.core.upload')->middleware('allow:core.config.core.upload');         
    });    

    // 系统功能
    $router->group(['prefix' =>'/system'], function (Router $router) {
        $router->post('/refresh','SystemController@refresh')->name('core.system.refresh')->middleware('allow:core.system.refresh');
        $router->get('/environment','SystemController@environment')->name('core.system.environment');       
        $router->get('/about','SystemController@about')->name('core.system.about');    
    });

    // 模块管理
    $router->group(['prefix' =>'/modules'], function (Router $router) {
        $router->get('/index','ModulesController@index')->name('core.modules.index')->middleware('allow:core.modules.index');
        $router->post('/enable/{name}','ModulesController@enable')->name('core.modules.enable')->middleware('allow:core.modules.enable');
        $router->post('/disable/{name}','ModulesController@disable')->name('core.modules.disable')->middleware('allow:core.modules.disable');
        $router->post('/install/{name}','ModulesController@install')->name('core.modules.install')->middleware('allow:core.modules.install');
        $router->post('/uninstall/{name}','ModulesController@uninstall')->name('core.modules.uninstall')->middleware('allow:core.modules.uninstall');
        $router->post('/delete/{name}','ModulesController@delete')->name('core.modules.delete')->middleware('allow:core.modules.delete');                 
    });

    // themes group example
    $router->group(['prefix' =>'/themes'], function (Router $router) {
        $router->get('/index','ThemesController@index')->name('core.themes.index')->middleware('allow:core.themes.index');
    });    

    // Plupload 模块后台路由
    $router->group(['prefix' =>'/plupload'], function (Router $router) {
        
        // 图片上传
        $router->post('/image', 'PluploadController@image')->name('core.plupload.image')->middleware('allow:core.upload.image');  
        
    });   

});
