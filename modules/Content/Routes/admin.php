<?php
use Illuminate\Routing\Router;

// Content 模块后台路由
$router->group(['prefix'=>'content', 'module'=>'content'], function (Router $router) {

    $router->get('index','ContentController@index')->name('content.content.index')->middleware('allow:content.content.index');
    $router->get('create','ContentController@create')->name('content.content.create')->middleware('allow:content.content.create');
    $router->post('store','ContentController@store')->name('content.content.store')->middleware('allow:content.content.store');
    $router->get('show/{id}','ContentController@show')->name('content.content.show')->middleware('allow:content.content.show');
    $router->get('edit/{id}','ContentController@edit')->name('content.content.edit')->middleware('allow:content.content.edit');
    $router->put('update/{id}','ContentController@update')->name('content.content.update')->middleware('allow:content.content.update');
    $router->delete('destroy/{id}','ContentController@destroy')->name('content.content.destroy')->middleware('allow:content.content.destroy');

    // 模型管理
    $router->group(['prefix' =>'model'], function (Router $router) {
        $router->get('index','ModelController@index')->name('content.model.index')->middleware('allow:content.model.index');
        $router->get('create','ModelController@create')->name('content.model.create')->middleware('allow:content.model.create');
        $router->post('store','ModelController@store')->name('content.model.store')->middleware('allow:content.model.store');
        $router->get('edit/{id}','ModelController@edit')->name('content.model.edit')->middleware('allow:content.model.edit');
        $router->put('update/{id}','ModelController@update')->name('content.model.update')->middleware('allow:content.model.update');
        $router->delete('destroy/{id}','ModelController@destroy')->name('content.model.destroy')->middleware('allow:content.model.destroy');
        $router->any('status/{id}','ModelController@status')->name('content.model.status')->middleware('allow:content.model.status');
        $router->any('export/{id}','ModelController@export')->name('content.model.export')->middleware('allow:content.model.export');
        $router->any('import','ModelController@import')->name('content.model.import')->middleware('allow:content.model.import');
        $router->any('sort','ModelController@sort')->name('content.model.sort')->middleware('allow:content.model.sort');
    }); 

    // 字段管理
    $router->group(['prefix' =>'field'], function (Router $router) {
        $router->get('index/{model_id}','FieldController@index')->name('content.field.index')->middleware('allow:content.field.index');
        $router->any('sort/{model_id}','FieldController@sort')->name('content.field.sort')->middleware('allow:content.field.sort');
        $router->get('create/{model_id}','FieldController@create')->name('content.field.create')->middleware('allow:content.field.create');
        $router->post('store','FieldController@store')->name('content.field.store')->middleware('allow:content.field.store');
        $router->get('show/{model_id}/{id}','FieldController@show')->name('content.field.show')->middleware('allow:content.field.show');
        $router->get('edit/{model_id}/{id}','FieldController@edit')->name('content.field.edit')->middleware('allow:content.field.edit');
        $router->put('update/{id}','FieldController@update')->name('content.field.update')->middleware('allow:content.field.update');
        $router->delete('destroy/{model_id}/{id}','FieldController@destroy')->name('content.field.destroy')->middleware('allow:content.field.destroy');
        $router->any('status/{model_id}/{id}','FieldController@status')->name('content.field.status')->middleware('allow:content.field.status');
        $router->any('settings/{model_id}','FieldController@settings')->name('content.field.settings');
    });
    
});
