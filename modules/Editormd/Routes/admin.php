<?php

use Illuminate\Routing\Router;

// Editormd admin路由
$router->group(['prefix'=>'editormd'], function (Router $router) {
    
    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('editormd.index')->middleware('allow:editormd.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('editormd.example.index')->middleware('allow:editormd.example.index');
    //    $router->get('create','ExampleController@create')->name('editormd.example.create')->middleware('allow:editormd.example.create');
    //    $router->post('store','ExampleController@store')->name('editormd.example.store')->middleware('allow:editormd.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('editormd.example.edit')->middleware('allow:editormd.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('editormd.example.update')->middleware('allow:editormd.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('editormd.example.destroy')->middleware('allow:editormd.example.destroy');                 
    // });    
    
});
