<?php
// 运行环境选项
return [
    'production' => [
        'title'       => trans('core::config.envs.production'),
        'description' => trans('core::config.envs.production.description'),
        'class'       => 'bg-primary border-primary text-white',
    ],
    'local'      => [
        'title'       =>trans('core::config.envs.local'),
        'description' =>trans('core::config.envs.local.description'),
        'class'       => 'bg-info border-info text-white',
    ],
    'testing'    => [
        'title'       =>trans('core::config.envs.testing'),
        'description' =>trans('core::config.envs.testing.description'),
        'class'       => 'bg-warning border-warning text-white',
    ],
];
