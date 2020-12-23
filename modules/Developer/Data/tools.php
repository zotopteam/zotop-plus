<?php

return [

    // 模块
    'module' => [
        'text'  => trans('developer::module.title'),
        'href'  => route('developer.module.index'),
        'icon'  => 'fa fa-puzzle-piece',
        'class' => 'bg-primary text-white',
    ],

    // 主题
    'theme'  => [
        'text'  => trans('developer::theme.title'),
        'href'  => route('developer.theme.index'),
        'icon'  => 'fa fa-tshirt',
        'class' => 'bg-success text-white',
    ],

    // 表单
    'form'   => [
        'text'  => trans('developer::form.title'),
        'href'  => route('developer.form.index'),
        'icon'  => 'fa fa-list-alt',
        'class' => 'bg-warning text-white',
    ],

    // 路由表
    'route'  => [
        'text'  => trans('developer::route.title'),
        'href'  => route('developer.route.index'),
        'icon'  => 'fa fa-link',
        'class' => 'bg-info text-white',
    ],
];
