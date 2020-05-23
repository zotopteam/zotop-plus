<?php
use Illuminate\Routing\Router;

$router->group(['prefix' =>'/core'], function (Router $router) {
    // append
});

$router->get('image/preview','ImageController@preview')->name('image.preview');          
