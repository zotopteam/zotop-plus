<?php
use Illuminate\Routing\Router;

// Section 模块后台路由
$router->group(['prefix'=>'section', 'module'=>'section'], function (Router $router) {
    
    $router->get('index','SectionController@index')->name('section.index')->middleware('allow:section.index');
    $router->get('create','SectionController@create')->name('section.create')->middleware('allow:section.create');
    $router->post('store','SectionController@store')->name('section.store')->middleware('allow:section.store');
    $router->get('show/{id}','SectionController@show')->name('section.show')->middleware('allow:section.show');
    $router->get('edit/{id}','SectionController@edit')->name('section.edit')->middleware('allow:section.edit');
    $router->put('update/{id}','SectionController@update')->name('section.update')->middleware('allow:section.update');
    $router->delete('destroy/{id}','SectionController@destroy')->name('section.destroy')->middleware('allow:section.destroy');
  
});
