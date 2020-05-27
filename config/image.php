<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => env('IMAGE_DRIVER', 'imagick'),

    /*
    |--------------------------------------------------------------------------
    | Image Previw
    |--------------------------------------------------------------------------
    |
    | 站内图片预览，支持以下模式
    | dynamic ：使用预览控制器动态生成预览图片
    | static ：在public/previews/images目录下生成预览图片
    */
    'preview' => [
        'mode'    => env('IMAGE_PREVIEW_MODE', 'static'),
        'dynamic' => [
            'lifetime' => 10, //缓存时间，单位分钟
        ],
        'static' => [
            'directory' => 'previews/images', // 将在 /public 目录下创建
        ],
    ],
);
