<?php
return [
    'console' => [
        'title'   => trans('developer::command.console.title'),
        'icon'    => 'fa fa-terminal',
        'artisan' => 'module:make-command',
        'name'    => ['append'=>'Command', 'label'=>trans('developer::command.console.name.label'), 'help'=>trans('developer::command.console.name.help')],
        'dir'     => 'Console',
    ],
    'request' => [
        'title'   => trans('developer::command.request.title'),
        'icon'    => 'fa fa-check-square',
        'artisan' => 'module:make-request',
        'name'    => ['append'=>'Request', 'label'=>trans('developer::command.request.name.label'), 'help'=>trans('developer::command.request.name.help')],
        'dir'     => 'Http/Requests',
    ],    
    'provider' => [
        'title'   => trans('developer::command.provider.title'),
        'icon'    => 'fa fa-user-secret',
        'artisan' => 'module:make-provider',
        'name'    => ['append'=>'ServiceProvider', 'label'=>trans('developer::command.provider.name.label'), 'help'=>trans('developer::command.provider.name.help')],
        'dir'     => 'Providers',
    ],
    'middleware' => [
        'title'   => trans('developer::command.middleware.title'),
        'icon'    => 'fa fa-align-center',
        'artisan' => 'module:make-middleware',
        'name'    => ['append'=>'Middleware', 'label'=>trans('developer::command.middleware.name.label'), 'help'=>trans('developer::command.middleware.name.help')],
        'dir'     => 'Http/Middleware',
    ],
    'event' => [
        'title'   => trans('developer::command.event.title'),
        'icon'    => 'fa fa-calendar-alt',
        'artisan' => 'module:make-event',
        'name'    => ['append'=>'', 'label'=>trans('developer::command.event.name.label'), 'help'=>trans('developer::command.event.name.help')],
        'dir'     => 'Events',
    ],
    'listener' => [
        'title'   => trans('developer::command.listener.title'),
        'icon'    => 'fa fa-headphones',
        'artisan' => 'module:make-listener',
        'name'    => ['append'=>'', 'label'=>trans('developer::command.listener.name.label'), 'help'=>trans('developer::command.listener.name.help')],
        'dir'     => 'Listeners',
    ],    
    'factory' => [
        'title'   => trans('developer::command.factory.title'),
        'icon'    => 'fa fa-warehouse',
        'artisan' => 'module:make-factory',
        'name'    => ['append'=>'Factory', 'label'=>trans('developer::command.factory.name.label'), 'help'=>trans('developer::command.factory.name.help')],
        'dir'     => 'Database/Factories',
    ],
    'seed' => [
        'title'   => trans('developer::command.seed.title'),
        'icon'    => 'fa fa-seedling',
        'artisan' => 'module:make-seed',
        'name'    => ['append'=>'TableSeeder', 'label'=>trans('developer::command.seed.name.label'), 'help'=>trans('developer::command.seed.name.help')],
        'dir'     => 'Database/Seeders',
    ],    
    'rule' => [
        'title'   => trans('developer::command.rule.title'),
        'icon'    => 'fa fa-ruler',
        'artisan' => 'module:make-rule',
        'name'    => ['append'=>'', 'label'=>trans('developer::command.rule.name.label'), 'help'=>trans('developer::command.rule.name.help')],
        'dir'     => 'Rules',
    ],
    'mail' => [
        'title'   => trans('developer::command.mail.title'),
        'icon'    => 'fa fa-envelope',
        'artisan' => 'module:make-mail',
        'name'    => ['append'=>'', 'label'=>trans('developer::command.mail.name.label'), 'help'=>trans('developer::command.mail.name.help')],
        'dir'     => 'Emails',
    ],  
                          
];
