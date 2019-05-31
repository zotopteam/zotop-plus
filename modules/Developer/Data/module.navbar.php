<?php
$navbar = [
    'info' => [
        'text'  => trans('developer::module.show'),
        'href'  => route('developer.module.show', [$module->name]),
        'icon'  => 'fa fa-info-circle',
        'class' => Route::active('developer.module.show'),
    ],
    'table' => [
        'text'  => trans('developer::table.title'),
        'href'  => route('developer.table.index', [$module->name]),
        'icon'  => 'fa fa-database',
        'class' => Route::active('developer.table.*'),
    ],
    'migration' => [
        'text'  => trans('developer::migration.title'),
        'href'  => route('developer.migration.index', [$module->name]),
        'icon'  => 'fa fa-database',
        'class' => Route::active('developer.migration.index'),
    ],      
    'model' => [
        'text'  => trans('developer::model.title'),
        'href'  => route('developer.model.index', [$module->name]),
        'icon'  => 'fa fa-cube',
        'class' => Route::active('developer.model.index'),
    ],     
    'controller' => [
        'text'  => trans('developer::controller.title'),
        'href'  => route('developer.controller.index', [$module->name, 'admin']),
        'icon'  => 'fa fa-sitemap',
        'class' => Route::active('developer.controller.index'),
    ],
    'permission' => [
        'text'  => trans('developer::permission.title'),
        'href'  => route('developer.permission.index', [$module->name]),
        'icon'  => 'fa fa-key',
        'class' => Route::active('developer.permission.index'),
    ],
    'translate' => [
        'text'  => trans('developer::translate.title'),
        'href'  => route('developer.translate.index', [$module->name]),
        'icon'  => 'fa fa-language',
        'class' => Route::active('developer.translate.*'),
    ],                    
];

$commands = \Module::data('developer::module.commands');

foreach ($commands as $key=>$command) {
    $navbar[$key] = [
        'text'  => $command['title'],
        'href'  => route('developer.command.index', [$module->name, $key]),
        'icon'  => $command['icon'],
        'class' => Route::is('developer.command.index') && Request::route('command') == $key ? 'active' : '',
    ];
}

return $navbar;
