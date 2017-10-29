<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements
    |
    */
    'php_version' => '7.0.0',

    'php_extensions'=> [
        'openssl',
        'pdo',
        'mbstring',
        'tokenizer',
        'JSON',
        'cURL',
        'GD',
        'fileinfo',
        'zip',
    ],

    'apache' => [
        'mod_rewrite',
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        '.env'               => '666',
        'public/'            => '775',
        'themes/'            => '775',
        'config/'            => '775',
        'modules/'           => '775',
        'storage/framework/' => '775',
        'storage/logs/'      => '775',
        'storage/plupload/'  => '775',
        'bootstrap/cache/'   => '775',
    ],


    /*
    |--------------------------------------------------------------------------
    | Updater Enabled
    |--------------------------------------------------------------------------
    | Can the application run the '/update' route with the migrations.
    | The default option is set to False if none is present.
    | Boolean value
    |
    */
    'updater' => 'true',
];
