<?php
return [
    'backend' => [
        'theme'  => 'admin',
        'prefix' => 'admin',
    ],
    'upload' => [
        'types' => [
            'image'    => ['enabled'=>1,'maxsize'=>20,'extensions'=>'jpg,jpeg,png,gif'],
            'document' => ['enabled'=>1,'maxsize'=>20,'extensions'=>'doc,docx,xls,xlsx,ppt,pptx,pps,pdf,txt'],
            'archive'  => ['enabled'=>1,'maxsize'=>20,'extensions'=>'rar,zip,7z'],         
            'video'    => ['enabled'=>1,'maxsize'=>20,'extensions'=>'mp4,mpeg,mov,avi,mpg,3gp,3g2'],
            'audio'    => ['enabled'=>1,'maxsize'=>20,'extensions'=>'mp3,midi,mid'],
        ],
        'dir' => 'Y/m',
        'url'  => '',
    ],
    'image' => [
        'resize' => [
            'enabled' => 1,
            'width'   => 1920,
            'height'  => 1200,
            'quality' => 90
        ],
        'watermark' => [
            'enabled'  => 1,
            'width'    => '500',
            'height'   => '500',
            'opacity'  => '50',
            'type'     => 'text',
            'text'     => '',
            'font'     => ['file'=>'resources/fonts/default.otf','size'=>36,'color'=>'#ffffff'],
            'image'    => '',
            'position' => 'bottom-right',
            'offset'   => ['x'=>10,'y'=>10],
            'quality'  => '90'
        ],
    ],
    'log' => [
        'enabled' => 1,
        'expire'  => 30, // 有效期，单位：天
    ],
    'notification' => [
        'check' => [
            'enabled' => 1,
            'interval' => 60, // 检查间隔时间，单位：秒
        ],
    ],
];
