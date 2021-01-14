<?php

use Illuminate\Routing\Router;

// Navbar admin路由
$router->group(['prefix' => 'navbar'], function (Router $router) {

// navbar
    $router->group(['prefix' => 'navbar'], function (Router $router) {
        $router->get('index', 'NavbarController@index')->name('navbar.navbar.index')->middleware('allow:navbar.navbar');
        $router->get('create', 'NavbarController@create')->name('navbar.navbar.create')->middleware('allow:navbar.navbar');
        $router->post('store', 'NavbarController@store')->name('navbar.navbar.store')->middleware('allow:navbar.navbar');
        $router->get('show/{id}', 'NavbarController@show')->name('navbar.navbar.show')->middleware('allow:navbar.navbar');
        $router->get('edit/{id}', 'NavbarController@edit')->name('navbar.navbar.edit')->middleware('allow:navbar.navbar');
        $router->put('update/{id}', 'NavbarController@update')->name('navbar.navbar.update')->middleware('allow:navbar.navbar');
        $router->delete('destroy/{id}', 'NavbarController@destroy')->name('navbar.navbar.destroy')->middleware('allow:navbar.navbar');
    });
    
});
