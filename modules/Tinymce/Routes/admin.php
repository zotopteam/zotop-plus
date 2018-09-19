<?php
use Illuminate\Routing\Router;

// Tinymce 模块后台路由
$router->group(['prefix'=>'tinymce', 'module'=>'tinymce'], function (Router $router) {
    
    // 首页
    $router->get('/', 'IndexController@index')->name('tinymce.index')->middleware('allow:tinymce.index');

    // 单个路由示例
    // $router->get('test2', 'IndexController@test2')->name('tinymce.test2')->middleware('allow:tinymce.test2');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('tinymce.example.index')->middleware('allow:tinymce.example.index');
    //    $router->get('create','ExampleController@create')->name('tinymce.example.create')->middleware('allow:tinymce.example.create');
    //    $router->post('store','ExampleController@store')->name('tinymce.example.store')->middleware('allow:tinymce.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('tinymce.example.edit')->middleware('allow:tinymce.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('tinymce.example.update')->middleware('allow:tinymce.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('tinymce.example.destroy')->middleware('allow:tinymce.example.destroy');                 
    // });    
    
});
