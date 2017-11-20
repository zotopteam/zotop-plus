<?php
return [
    'upload' => [
        'text'   => trans('core::config.upload'),
        'href'   => route('core.config.upload'),
        'class'  => 'fa fa-fw fa-upload',
        'active' => Route::is('core.config.upload')
    ],
    'mail'   => [
        'text'   => trans('core::config.mail'),
        'href'   => route('core.config.mail'),
        'class'  => 'fa fa-fw fa-envelope',
        'active' => Route::is('core.config.mail'),
    ],
    'locale'   => [
        'text'   => trans('core::config.locale'),
        'href'   => route('core.config.locale'),
        'class'  => 'fa fa-fw fa-map',
        'active' => Route::is('core.config.locale'),
    ],            
    'safe'   => [
        'text'   => trans('core::config.safe'),
        'href'   => route('core.config.safe'),
        'class'  => 'fa fa-fw fa-shield',
        'active' => Route::is('core.config.safe')
    ],
];
