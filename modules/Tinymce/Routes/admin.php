<?php
use Illuminate\Routing\Router;

// Tinymce 模块后台路由
$router->group(['prefix'=>'tinymce'], function (Router $router) {
    
    // upload
    $router->group(['prefix' =>'upload'], function (Router $router) {
        $router->any('file', 'UploadController@file')->name('tinymce.upload.file');
    });
    
});
