<?php

use Illuminate\Routing\Router;

// Navbar front路由
$router->group(['prefix' =>'navbar'], function (Router $router) {
    
    // 单个路由示例
    //$router->get('/', 'IndexController@index')->name('navbar.index');

    // 群组路由示例
    // $router->group(['prefix' =>'example'], function (Router $router) {
    //    $router->get('index','ExampleController@index')->name('navbar.example.index');
    //    $router->get('create','ExampleController@create')->name('navbar.example.create');
    //    $router->post('store','ExampleController@store')->name('navbar.example.store');    
    //    $router->get('edit/{id}','AdministratorController@edit')->name('navbar.example.edit');
    //    $router->put('update/{id}','AdministratorController@update')->name('navbar.example.update');
    //    $router->delete('destroy/{id}','AdministratorController@destroy')->name('navbar.example.destroy');                 
    // }); 
       
});
