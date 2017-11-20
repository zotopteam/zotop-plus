<?php
return [
    'administrator' => [
        'text'   => trans('core::administrator.title'),
        'href'   => route('core.administrator.index'),
        'class'  => 'fa fa-fw fa-users',
        'active' => Route::is('core.administrator.index'),
    ],
    'role'          => [
        'text'   => trans('core::role.title'),
        'href'   => route('core.role.index'),
        'class'  => 'fa fa-fw fa-sitemap',
        'active' => Route::is('core.role.index'),
    ],            
];
