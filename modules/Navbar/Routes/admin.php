<?php

use Illuminate\Routing\Router;

// Navbar admin路由
$router->group(['prefix' => 'navbar'], function (Router $router) {

// navbar
    $router->group(['prefix' => 'navbar'], function (Router $router) {
        $router->get('index', 'NavbarController@index')->name('navbar.navbar.index')->middleware('allow:navbar.navbar');
        $router->post('sort', 'NavbarController@sort')->name('navbar.navbar.sort')->middleware('allow:navbar.navbar');
        $router->get('create', 'NavbarController@create')->name('navbar.navbar.create')->middleware('allow:navbar.navbar');
        $router->post('store', 'NavbarController@store')->name('navbar.navbar.store')->middleware('allow:navbar.navbar');
        $router->get('show/{id}', 'NavbarController@show')->name('navbar.navbar.show')->middleware('allow:navbar.navbar');
        $router->get('edit/{id}', 'NavbarController@edit')->name('navbar.navbar.edit')->middleware('allow:navbar.navbar');
        $router->put('update/{id}', 'NavbarController@update')->name('navbar.navbar.update')->middleware('allow:navbar.navbar');
        $router->delete('destroy/{id}', 'NavbarController@destroy')->name('navbar.navbar.destroy')->middleware('allow:navbar.navbar');
        $router->post('enable/{id}', 'NavbarController@enable')->name('navbar.navbar.enable')->middleware('allow:navbar.navbar');
        $router->post('disable/{id}', 'NavbarController@disable')->name('navbar.navbar.disable')->middleware('allow:navbar.navbar');
    });

    // item
    $router->group(['prefix' => 'item'], function (Router $router) {
        $router->get('{navbar_id}/index/{parent_id?}', 'ItemController@index')->name('navbar.item.index')->middleware('allow:navbar.item');
        $router->post('{navbar_id}/sort/{parent_id?}', 'ItemController@sort')->name('navbar.item.sort')->middleware('allow:navbar.item');
        $router->get('{navbar_id}/create/{parent_id?}', 'ItemController@create')->name('navbar.item.create')->middleware('allow:navbar.item');
        $router->post('store', 'ItemController@store')->name('navbar.item.store')->middleware('allow:navbar.item');
        $router->get('show/{id}', 'ItemController@show')->name('navbar.item.show')->middleware('allow:navbar.item');
        $router->get('edit/{id}', 'ItemController@edit')->name('navbar.item.edit')->middleware('allow:navbar.item');
        $router->put('update/{id}', 'ItemController@update')->name('navbar.item.update')->middleware('allow:navbar.item');
        $router->delete('destroy/{id}', 'ItemController@destroy')->name('navbar.item.destroy')->middleware('allow:navbar.item');
        $router->post('enable/{id}', 'ItemController@enable')->name('navbar.item.enable')->middleware('allow:navbar.item');
        $router->post('disable/{id}', 'ItemController@disable')->name('navbar.item.disable')->middleware('allow:navbar.item');
    });

    // field
    $router->group(['prefix' => 'field'], function (Router $router) {
        $router->get('{navbar_id}/index/{parent_id?}', 'FieldController@index')->name('navbar.field.index')->middleware('allow:navbar.field');
        $router->get('{navbar_id}/create/{parent_id?}', 'FieldController@create')->name('navbar.field.create')->middleware('allow:navbar.field');
        $router->any('{navbar_id}/sort/{parent_id?}', 'FieldController@sort')->name('navbar.field.sort')->middleware('allow:navbar.field');
        $router->post('store', 'FieldController@store')->name('navbar.field.store')->middleware('allow:navbar.field');
        $router->get('show/{id}', 'FieldController@show')->name('navbar.field.show')->middleware('allow:navbar.field');
        $router->get('edit/{id}', 'FieldController@edit')->name('navbar.field.edit')->middleware('allow:navbar.field');
        $router->put('update/{id}', 'FieldController@update')->name('navbar.field.update')->middleware('allow:navbar.field');
        $router->delete('destroy/{id}', 'FieldController@destroy')->name('navbar.field.destroy')->middleware('allow:navbar.field');
        $router->any('settings', 'FieldController@settings')->name('navbar.field.settings')->middleware('allow:navbar.field');
        $router->post('enable/{id}', 'FieldController@enable')->name('navbar.field.enable')->middleware('allow:navbar.field');
        $router->post('disable/{id}', 'FieldController@disable')->name('navbar.field.disable')->middleware('allow:navbar.field');
    });
});
