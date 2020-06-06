<?php

// 文件磁盘
return [
    'public'  => [
        'icon'        => 'fa fa-hdd',
        'type'        => 'public',
        'text'        => trans('core::storage.disk.public.title'),
        'description' => trans('core::storage.disk.public.description'),
        'href'        => route('core.storage.index', 'public'),
    ],
    'private'  => [
        'icon'        => 'fa fa-hdd',
        'type'        => 'private',
        'text'        => trans('core::storage.disk.private.title'),
        'description' => trans('core::storage.disk.private.description'),
        'href'        => route('core.storage.index', 'private'),
    ],
];
