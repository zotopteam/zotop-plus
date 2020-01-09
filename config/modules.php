<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path() . '/vendor/nwidart/zotop-modules/src/Commands/stubs',
        'files' => [
            // 'start' => 'start.php',
            // 'routes' => 'Http/routes.php',
            // 'views/index' => 'Resources/views/index.blade.php',
            // 'views/master' => 'Resources/views/layouts/master.blade.php',
            // 'scaffold/config' => 'Config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'Resources/assets/js/app.js',
            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            'webpack' => 'webpack.mix.js',
            'package' => 'package.json',            
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME'],
            'webpack' => ['LOWER_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
            ],
        ],
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */

        'assets' => public_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Se the generate key to false to not generate that folder
        */
        'generator' => [
            'config'        => ['path' => 'Config', 'generate' => false],
            'data'          => ['path' => 'Data', 'generate' => true],
            'command'       => ['path' => 'Console', 'generate' => true],
            'migration'     => ['path' => 'Database/Migrations', 'generate' => true],
            'seeder'        => ['path' => 'Database/Seeders', 'generate' => true],
            'factory'       => ['path' => 'Database/Factories', 'generate' => true],
            'model'         => ['path' => 'Models', 'generate' => true],
            'controller'    => ['path' => 'Http/Controllers', 'generate' => true],
            'filter'        => ['path' => 'Http/Middleware', 'generate' => true],
            'request'       => ['path' => 'Http/Requests', 'generate' => true],
            'provider'      => ['path' => 'Providers', 'generate' => true],
            'assets'        => ['path' => 'Resources/assets', 'generate' => true],
            'lang'          => ['path' => 'Resources/lang', 'generate' => true],
            'views'         => ['path' => 'Resources/views', 'generate' => true],
            'test'          => ['path' => 'Tests', 'generate' => false],
            'repository'    => ['path' => 'Repositories', 'generate' => false],
            'event'         => ['path' => 'Events', 'generate' => false],
            'listener'      => ['path' => 'Listeners', 'generate' => false],
            'policies'      => ['path' => 'Policies', 'generate' => false],
            'rules'         => ['path' => 'Rules', 'generate' => false],
            'jobs'          => ['path' => 'Jobs', 'generate' => false],
            'emails'        => ['path' => 'Emails', 'generate' => false],
            'notifications' => ['path' => 'Notifications', 'generate' => false],
            'resource'      => ['path' => 'Transformers', 'generate' => false],
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths'   => [
            base_path('vendor/*/*'),
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Composer 设置
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */

    'composer' => [
        'vendor' => 'zotop',
        'author' => [
            'name'  => 'ZotopTeam',
            'email' => 'cms@zotop.com',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | 缓存设置
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled'  => true,
        'key'      => 'zotop-modules',
        'lifetime' => 60,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | 核心模块
    | 系统必须按照的核心模块，禁止禁用、卸载、删除
    | Add by hankx_chen
    |--------------------------------------------------------------------------
    */
    'cores' => [
        'core',
        'site',
    ],    
];
