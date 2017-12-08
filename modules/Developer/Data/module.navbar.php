<?php
return [
    'info' => [
        'text'  => trans('developer::module.show'),
        'href'  => route('developer.module.show',[$module->name]),
        'icon'  => 'fa fa-info-circle',
        'class' => Route::active('developer.module.show'),
    ],
    'controller' => [
        'text'  => trans('developer::controller.title'),
        'href'  => route('developer.controller.index',[$module->name, 'admin']),
        'icon'  => 'fa fa-sitemap',
        'class' => Route::active('developer.controller.index'),
    ], 
    'migration' => [
        'text'  => trans('developer::migration.title'),
        'href'  => route('developer.migration.index',[$module->name]),
        'icon'  => 'fa fa-database',
        'class' => Route::active('developer.migration.index'),
    ],
    'command' => [
        'text'  => trans('developer::command.title'),
        'href'  => route('developer.command.index',[$module->name]),
        'icon'  => 'fa fa-terminal',
        'class' => Route::active('developer.command.index'),
    ],
    'permission' => [
        'text'  => trans('developer::permission.title'),
        'href'  => route('developer.permission.index',[$module->name]),
        'icon'  => 'fa fa-key',
        'class' => Route::active('developer.permission.index'),
    ],                  
];
