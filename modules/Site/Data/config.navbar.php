<?php
return [
    'base' => [
        'text'  => trans('site::config.base'),
        'href'  => route('site.config.base'),
        'icon'  => 'fa fa-cog',
        'class' => Route::active('site.config.base'),
    ],
    'seo' => [
        'text'  => trans('site::config.seo'),
        'href'  => route('site.config.seo'),
        'icon'  => 'fa fa-search',
        'class' => Route::active('site.config.seo'),
    ], 
    'maintain' => [
        'text'  => trans('site::config.maintain'),
        'href'  => route('site.config.maintain'),
        'icon'  => 'fa fa-power-off',
        'class' => Route::active('site.config.maintain'),
    ],              
];
