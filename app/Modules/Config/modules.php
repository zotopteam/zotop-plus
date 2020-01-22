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
            'data'             => 'Data',
            'command'          => 'Console',
            'migration'        => 'Database/Migrations',
            'seeder'           => 'Database/Seeders',
            'factory'          => 'Database/Factories',
            'model'            => 'Models',
            'controller'       => 'Http/Controllers',
            'middleware'       => 'Http/Middleware',
            'request'          => 'Http/Requests',
            'provider'         => 'Providers',
            'assets'           => 'Resources/assets',
            'lang'             => 'Resources/lang',
            'views'            => 'Resources/views',
            'test'             => 'Tests',
            'repository'       => 'Repositories',
            'events'           => 'Events',
            'hook'             => 'Hooks',
            'listener'         => 'Listeners',
            'policies'         => 'Policies',
            'rules'            => 'Rules',
            'jobs'             => 'Jobs',
            'mails'            => 'Mails',
            'notifications'    => 'Notifications',
            'traits'           => 'Traits',
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
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | 前端、后端和api端定义
    |--------------------------------------------------------------------------
    */
    'types' => [
        'frontend' => [
            'prefix'     => '',
            'middleware' => ['web','module','front'],
            'theme'      => 'default',
            'dirs'       => [
                'controller' => '',
                'view'       => 'front',
            ],
        ],
        'backend' => [
            'prefix'     => 'admin',
            'middleware' => ['web','module','admin'],
            'theme'      => 'admin',            
            'dirs'       => [
                'controller' => 'Admin',
                'view'       => 'admin',
            ]
        ],
        'api' => [
            'prefix'     => 'api',
            'middleware' => ['api', 'module'],
            'theme'      => 'api',            
            'dirs'       => [
                'controller' => 'Api',
                'view'       => 'api',
            ]
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
    'cache' => [
        'enabled'  => true,
        'key'      => 'zotop-modules',
        'lifetime' => 60,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | 核心模块
    | 系统必须按照的核心模块，禁止禁用、卸载、删除
    |--------------------------------------------------------------------------
    */
    'cores' => [
        'core',
    ],    
];
