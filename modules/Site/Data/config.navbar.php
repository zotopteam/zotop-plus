<?php
return [
    'base' => [
        'text'  => trans('site::config.base'),
        'href'  => route('site.config.base'),
        'icon'  => 'fa-cog',
        'class' => Route::active('site.config.base'),
    ],
    'seo' => [
        'text'  => trans('site::config.seo'),
        'href'  => route('site.config.seo'),
        'icon'  => 'fa-search',
        'class' => Route::active('site.config.seo'),
    ], 
    'close' => [
        'text'  => trans('site::config.close'),
        'href'  => route('site.config.close'),
        'icon'  => 'fa-power-off',
        'class' => Route::active('site.config.close'),
    ],              
];
