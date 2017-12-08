<?php
return [
    'administrator' => [
        'text'   => trans('core::administrator.title'),
        'href'   => route('core.administrator.index'),
        'icon'  => 'fa fa-users',
        'active' => Route::active('core.administrator.index'),
    ],
    'role'          => [
        'text'   => trans('core::role.title'),
        'href'   => route('core.role.index'),
        'icon'  => 'fa fa-sitemap',
        'active' => Route::active('core.role.index'),
    ],            
];
