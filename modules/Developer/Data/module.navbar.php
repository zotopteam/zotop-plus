<?php
$navbar = [
    'info' => [
        'text'  => trans('developer::module.show'),
        'href'  => route('developer.module.show', [$module]),
        'icon'  => 'fa fa-info-circle',
        'class' => Route::active('developer.module.show'),
    ],
    'table' => [
        'text'  => trans('developer::table.title'),
        'href'  => route('developer.table.index', [$module]),
        'icon'  => 'fa fa-database',
        'class' => Route::active('developer.table.*'),
    ],
    'migration' => [
        'text'  => trans('developer::migration.title'),
        'href'  => route('developer.migration.index', [$module]),
        'icon'  => 'fa fa-database',
        'class' => Route::active('developer.migration.index'),
    ],      
    'model' => [],   
    'controller' => [
        'text'  => trans('developer::controller.title'),
        'href'  => route('developer.controller.index', [$module, 'backend']),
        'icon'  => 'fa fa-sitemap',
        'class' => Route::active('developer.controller.index'),
    ],
    'permission' => [
        'text'  => trans('developer::permission.title'),
        'href'  => route('developer.permission.index', [$module]),
        'icon'  => 'fa fa-key',
        'class' => Route::active('developer.permission.index'),
    ],
    'translate' => [
        'text'  => trans('developer::translate.title'),
        'href'  => route('developer.translate.index', [$module]),
        'icon'  => 'fa fa-language',
        'class' => Route::active('developer.translate.*'),
    ],                    
];

$commands = \Module::data('developer::module.commands');

foreach ($commands as $key=>$command) {
    $navbar[$key] = [
        'text'  => $command['title'],
        'href'  => route('developer.command.index', [$module, $key]),
        'icon'  => $command['icon'],
        'class' => Route::is('developer.command.index') && Request::route('key') == $key ? 'active' : '',
    ];
}

return $navbar;
