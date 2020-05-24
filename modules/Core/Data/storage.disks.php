<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// 文件磁盘
return [
    'public'  => [
        'icon'        => 'fa fa-hdd',
        'type'        => 'public',
        'title'       => trans('core::storage.disk.public.title'),
        'description' => trans('core::storage.disk.public.description'),
        'href'        => route('core.storage.index', 'public'),
        'class'       => Route::is('core.storage.index') && Route::current()->parameter('disk') == 'public' ? 'active' : '',
    ],    
    'private'  => [
        'icon'        => 'fa fa-hdd',
        'type'        => 'private',
        'title'       => trans('core::storage.disk.private.title'),
        'description' => trans('core::storage.disk.private.description'),
        'href'        => route('core.storage.index', 'private'),
        'class'       => Route::is('core.storage.index') && Route::current()->parameter('disk') == 'private' ? 'active' : '',
    ],   
];
