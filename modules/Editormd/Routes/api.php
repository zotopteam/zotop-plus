<?php

use Illuminate\Routing\Router;

// Editormd api路由
$router->group(['prefix' =>'editormd'], function (Router $router) {
    
    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('editormd.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('editormd.example.index');
    //    $router->get('create','ExampleController@create')->name('editormd.example.create');
    //    $router->post('store','ExampleController@store')->name('editormd.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('editormd.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('editormd.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('editormd.example.destroy');                 
    // }); 
       
});
