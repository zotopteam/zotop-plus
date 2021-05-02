<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules namespace
    |--------------------------------------------------------------------------
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Modules paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        */

        'modules' => base_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        */

        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | Module dirs
        |--------------------------------------------------------------------------
        |
        */

        'dirs' => [
            'data'          => 'Data',
            'command'       => 'Console',
            'migration'     => 'Database/Migrations',
            'seeder'        => 'Database/Seeders',
            'factory'       => 'Database/Factories',
            'model'         => 'Models',
            'filter'        => 'Models/QueryFilters',
            'controller'    => 'Http/Controllers',
            'middleware'    => 'Http/Middleware',
            'request'       => 'Http/Requests',
            'provider'      => 'Providers',
            'assets'        => 'Resources/assets',
            'lang'          => 'Resources/lang',
            'views'         => 'Resources/views',
            'test'          => 'Tests',
            'repository'    => 'Repositories',
            'service'       => 'Services',
            'events'        => 'Events',
            'hook'          => 'Hooks',
            'listener'      => 'Listeners',
            'policies'      => 'Policies',
            'rules'         => 'Rules',
            'jobs'          => 'Jobs',
            'mails'         => 'Mails',
            'notifications' => 'Notifications',
            'traits'        => 'Traits',
            'components'    => 'View/Components',
            'controls'      => 'View/Controls',
            'enums'         => 'Enums',
        ],

        /*
        |--------------------------------------------------------------------------
        | Module files
        |--------------------------------------------------------------------------
        |
        */

        'files' => [
            'module'         => 'module.json',
            'composer'       => 'composer.json',
            'start'          => 'start.php',
            'config'         => 'config.php',
            'permission'     => 'permission.php',
            'routes/admin'   => 'Routes/admin.php',
            'routes/api'     => 'Routes/api.php',
            'routes/front'   => 'Routes/front.php',
            'routes/console' => 'Routes/console.php',
            'gitkeep/asset'  => 'Resources/assets/.gitkeep',
            'gitkeep/view'   => 'Resources/views/.gitkeep',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 渠道
    |--------------------------------------------------------------------------
    */

    'channels' => [
        'front' => [
            'prefix'     => '',
            'middleware' => ['web', 'module', 'theme', 'front'],
            'route'      => 'front.php',
            'theme'      => 'default',
            'dirs'       => [
                'controller' => '',
                'request'    => '',
                'view'       => 'front',
            ],
        ],
        'admin' => [
            'prefix'     => 'admin',
            'middleware' => ['web', 'module', 'theme', 'admin'],
            'route'      => 'admin.php',
            'theme'      => 'admin',
            'dirs'       => [
                'controller' => 'Admin',
                'request'    => 'Admin',
                'view'       => 'admin',
            ],
        ],
        'api'   => [
            'prefix'     => 'api',
            'middleware' => ['api', 'module'],
            'route'      => 'api.php',
            'theme'      => '',
            'dirs'       => [
                'controller' => 'Api',
                'request'    => 'Api',
                'view'       => 'api',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer 设置
    |--------------------------------------------------------------------------
    */

    'composer' => [
        'vendor' => 'zotop',
        'author' => [
            'name'     => 'ZotopTeam',
            'email'    => 'cms@zotop.com',
            'homepage' => 'http://www.zotop.com',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 缓存设置
    |--------------------------------------------------------------------------
    */
    'cache'    => [
        'enabled'  => true,
        'key'      => 'zotop-modules',
        'lifetime' => 60,
    ],

];
