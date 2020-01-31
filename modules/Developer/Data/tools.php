<?php

return [
    'module' => [
        'text'  => trans('developer::module.title'),
        'href'  => route('developer.module.index'),
        'icon'  => 'fa fa-puzzle-piece',
        'class' => 'bg-primary text-white',
    ],
    'theme' => [
        'text'  => trans('developer::theme.title'),
        'href'  => route('developer.theme.index'),
        'icon'  => 'fa fa-tshirt',
        'class' => 'bg-success text-white',
    ],
    'route' => [
        'text'  => trans('developer::route.title'),
        'href'  => route('developer.route.index'),
        'icon'  => 'fa fa-link',
        'class' => 'bg-info text-white',
    ],    
];
