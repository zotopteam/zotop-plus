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
    'php_version' => '7.3.0',

    'php_extensions' => [
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

    'apache'      => [
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
        'public/'            => '0755',
        'themes/'            => '0755',
        'config/'            => '0755',
        'modules/'           => '0755',
        'storage/framework/' => '0755',
        'storage/logs/'      => '0755',
        'storage/plupload/'  => '0755',
        'bootstrap/cache/'   => '0755',
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
    'updater'     => 'true',
];
