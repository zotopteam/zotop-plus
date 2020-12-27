<?php
return [

    // model

    'model' => [
        'title'   => trans('developer::command.model.title'),
        'icon'    => 'fa fa-cube',
        'command' => 'module:make-model',
        'name'    => ['label' => trans('developer::command.model.name.label'), 'help' => trans('developer::command.model.name.help')],
        'dir'     => config('modules.paths.dirs.model'),
        'options' => ['--table' => 'developer::command.options.model_table'],
        'help'    => trans('developer::command.model.help'),
    ],

    'filter' => [
        'title'   => trans('developer::command.filter.title'),
        'icon'    => 'fa fa-filter',
        'command' => 'module:make-filter',
        'name'    => ['label' => trans('developer::command.filter.name.label'), 'help' => trans('developer::command.filter.name.help')],
        'dir'     => config('modules.paths.dirs.filter'),
        'options' => ['--table' => 'developer::command.options.filter_table'],
        'help'    => trans('developer::command.filter.help'),
    ],

    // console

    'console' => [
        'title'   => trans('developer::command.console.title'),
        'icon'    => 'fa fa-terminal',
        'command' => 'module:make-command',
        'name'    => ['label' => trans('developer::command.console.name.label'), 'help' => trans('developer::command.console.name.help')],
        'dir'     => config('modules.paths.dirs.command'),
        'help'    => trans('developer::command.console.help'),
    ],

    // request

    'request' => [
        'title'   => trans('developer::command.request.title'),
        'icon'    => 'fa fa-check-square',
        'command' => 'module:make-request',
        'name'    => ['label' => trans('developer::command.request.name.label'), 'help' => trans('developer::command.request.name.help')],
        'dir'     => config('modules.paths.dirs.request'),
    ],

    // component

    'component' => [
        'title'   => trans('developer::command.component.title'),
        'icon'    => 'fa fa-cube',
        'command' => 'module:make-component',
        'name'    => ['label' => trans('developer::command.component.name.label'), 'help' => trans('developer::command.component.name.help')],
        'options' => ['--view' => 'developer::command.options.component_view'],
        'dir'     => config('modules.paths.dirs.components'),
        'help'    => trans('developer::command.component.help'),
    ],

    // component

    'control' => [
        'title'   => trans('developer::command.control.title'),
        'icon'    => 'fa fa-cube',
        'command' => 'module:make-control',
        'name'    => ['label' => trans('developer::command.control.name.label'), 'help' => trans('developer::command.control.name.help')],
        'options' => ['--view' => 'developer::command.options.control_view'],
        'dir'     => config('modules.paths.dirs.controls'),
        'help'    => trans('developer::command.control.help'),
    ],

    // middleware

    'middleware' => [
        'title'   => trans('developer::command.middleware.title'),
        'icon'    => 'fa fa-align-center',
        'command' => 'module:make-middleware',
        'name'    => ['label' => trans('developer::command.middleware.name.label'), 'help' => trans('developer::command.middleware.name.help')],
        'dir'     => config('modules.paths.dirs.middleware'),
        'help'    => trans('developer::command.middleware.help'),
    ],

    // provider

    'provider' => [
        'title'   => trans('developer::command.provider.title'),
        'icon'    => 'fa fa-user-secret',
        'command' => 'module:make-provider',
        'name'    => ['label' => trans('developer::command.provider.name.label'), 'help' => trans('developer::command.provider.name.help')],
        'options' => ['--type' => 'developer::command.options.provider_type'],
        'dir'     => config('modules.paths.dirs.provider'),
        'help'    => trans('developer::command.provider.help'),
    ],

    // event

    'event' => [
        'title'   => trans('developer::command.event.title'),
        'icon'    => 'fa fa-calendar-alt',
        'command' => 'module:make-event',
        'name'    => ['label' => trans('developer::command.event.name.label'), 'help' => trans('developer::command.event.name.help')],
        'dir'     => config('modules.paths.dirs.events'),
    ],

    // --event=，--queued

    'listener' => [
        'title'   => trans('developer::command.listener.title'),
        'icon'    => 'fa fa-headphones',
        'command' => 'module:make-listener',
        'name'    => ['label' => trans('developer::command.listener.name.label'), 'help' => trans('developer::command.listener.name.help')],
        'options' => ['--event' => 'developer::command.options.listener_event', '--queued' => 'developer::command.options.listener_queued'],
        'dir'     => config('modules.paths.dirs.listener'),
    ],

    // --sync 属性

    'job' => [
        'title'   => trans('developer::command.job.title'),
        'icon'    => 'fa fa-tasks',
        'command' => 'module:make-job',
        'name'    => ['label' => trans('developer::command.job.name.label'), 'help' => trans('developer::command.job.name.help')],
        'options' => ['--sync' => 'developer::command.options.job_sync'],
        'dir'     => config('modules.paths.dirs.jobs'),
    ],

    // factory --sync 属性

    'factory' => [
        'title'   => trans('developer::command.factory.title'),
        'icon'    => 'fa fa-warehouse',
        'command' => 'module:make-factory',
        'name'    => ['label' => trans('developer::command.factory.name.label'), 'help' => trans('developer::command.factory.name.help')],
        'dir'     => config('modules.paths.dirs.factory'),
    ],

    // --master

    'seeder' => [
        'title'   => trans('developer::command.seeder.title'),
        'icon'    => 'fa fa-seedling',
        'command' => 'module:make-seeder',
        'name'    => ['label' => trans('developer::command.seeder.name.label'), 'help' => trans('developer::command.seeder.name.help')],
        'options' => ['--master' => 'developer::command.options.seeder_master'],
        'dir'     => config('modules.paths.dirs.seeder'),
    ],

    // rule

    'rule' => [
        'title'   => trans('developer::command.rule.title'),
        'icon'    => 'fa fa-ruler',
        'command' => 'module:make-rule',
        'name'    => ['label' => trans('developer::command.rule.name.label'), 'help' => trans('developer::command.rule.name.help')],
        'dir'     => config('modules.paths.dirs.rules'),
    ],

    // notification

    'notification' => [
        'title'   => trans('developer::command.notification.title'),
        'icon'    => 'fa fa-bell',
        'command' => 'module:make-notification',
        'name'    => ['label' => trans('developer::command.notification.name.label'), 'help' => trans('developer::command.notification.name.help')],
        'dir'     => config('modules.paths.dirs.notifications'),
    ],

    // notification

    'mail' => [
        'title'   => trans('developer::command.mail.title'),
        'icon'    => 'fa fa-envelope',
        'command' => 'module:make-mail',
        'name'    => ['label' => trans('developer::command.mail.name.label'), 'help' => trans('developer::command.mail.name.help')],
        'dir'     => config('modules.paths.dirs.mails'),
    ],

    // policy --model=

    'policy' => [
        'title'   => trans('developer::command.policy.title'),
        'icon'    => 'fa fa-user-shield',
        'command' => 'module:make-policy',
        'name'    => ['label' => trans('developer::command.policy.name.label'), 'help' => trans('developer::command.policy.name.help')],
        'dir'     => config('modules.paths.dirs.policies'),
        'help'    => trans('developer::command.policy.help'),
    ],

    //  hook

    'hook' => [
        'title'   => trans('developer::command.hook.title'),
        'icon'    => 'fa fa-tools',
        'command' => 'module:make-hook',
        'name'    => ['label' => trans('developer::command.hook.name.label'), 'help' => trans('developer::command.hook.name.help')],
        'dir'     => config('modules.paths.dirs.hook'),
    ],

    // data

    'data' => [
        'title'   => trans('developer::command.data.title'),
        'icon'    => 'fa fa-save',
        'command' => 'module:make-data',
        'name'    => [
            'label'   => trans('developer::command.data.name.label'),
            'help'    => trans('developer::command.data.name.help'),
            'pattern' => '^[a-z][a-z0-9._-]+[a-z0-9]$',
        ],
        'dir'     => config('modules.paths.dirs.data'),
    ],

    // trait

    'trait' => [
        'title'   => trans('developer::command.trait.title'),
        'icon'    => 'fa fa-code',
        'command' => 'module:make-trait',
        'name'    => ['label' => trans('developer::command.trait.name.label'), 'help' => trans('developer::command.trait.name.help')],
        'dir'     => config('modules.paths.dirs.traits'),
    ],

    // enum

    'enum' => [
        'title'   => trans('developer::command.enum.title'),
        'icon'    => 'fa fa-list-alt',
        'command' => 'module:make-enum',
        'name'    => ['label' => trans('developer::command.enum.name.label'), 'help' => trans('developer::command.enum.name.help')],
        'dir'     => config('modules.paths.dirs.enums'),
    ],

    // --unit

    'test' => [
        'title'   => trans('developer::command.test.title'),
        'icon'    => 'fa fa-vial',
        'command' => 'module:make-test',
        'name'    => ['label' => trans('developer::command.test.name.label'), 'help' => trans('developer::command.test.name.help')],
        'options' => ['--type' => 'developer::command.options.test_types'],
        'dir'     => config('modules.paths.dirs.test'),
    ],
];
