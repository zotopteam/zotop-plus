<?php
return [
    'upload' => [
        'text'   => trans('core::config.upload'),
        'href'   => route('core.config.upload'),
        'icon'  => 'fa fa-upload',
        'active' => Route::is('core.config.upload')
    ],
    'mail'   => [
        'text'   => trans('core::config.mail'),
        'href'   => route('core.config.mail'),
        'icon'  => 'fa fa-envelope',
        'active' => Route::is('core.config.mail'),
    ],
    'locale'   => [
        'text'   => trans('core::config.locale'),
        'href'   => route('core.config.locale'),
        'icon'  => 'fa fa-map',
        'active' => Route::is('core.config.locale'),
    ],            
    'safe'   => [
        'text'   => trans('core::config.safe'),
        'href'   => route('core.config.safe'),
        'icon'  => 'fa fa-shield-alt',
        'active' => Route::is('core.config.safe')
    ],
];
