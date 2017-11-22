<?php
return [
    'theme'  => 'admin',
    'skin'   => 'default',
    'upload' => [
        'types' => [
            'image' => ['enabled'=>1,'maxsize'=>20,'extensions'=>'jpg,jpeg,png,gif'],
            'files' => ['enabled'=>1,'maxsize'=>20,'extensions'=>'doc,docx,xls,xlsx,ppt,pptx,pps,pdf,txt,rar,zip,7z'],            
            'video' => ['enabled'=>1,'maxsize'=>20,'extensions'=>'mp4,mpeg,mov,avi,mpg,3gp,3g2'],
            'audio' => ['enabled'=>1,'maxsize'=>20,'extensions'=>'mp3,midi,mid'],
        ],
        'dir' => 'Y/m',
        'url'  => '',
    ],
    'image' => [
        'resize' => ['enabled'=>1,'width'=>1980,'height'=>'1200','quality'=>100],
        'watermark' => [
            'enabled'  => 1,
            'width'    => '300',
            'height'   => '200',
            'opacity'  => '90',
            'type'     => 'text',
            'text'     => '',
            'font'     => ['file'=>'/resources/watermark/fonts/default.otf','size'=>18,'color'=>'#ffffff'],
            'image'    => '',
            'position' => 'bottom-right',
            'quality'  => '100'
        ],
    ],
];
