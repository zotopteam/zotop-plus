<?php
$upload_tools = [
    'image' => array('type'=>'image','text'=>trans('core::file.type.image'),'title'=>trans('core::file.type.image'),'icon'=>'image','route'=>route('media.select.uploaded')),
    'file'  => array('type'=>'files','text'=>trans('core::file.type.files'),'title'=>trans('core::file.type.files'),'icon'=>'fas fa-file','route'=>route('media.select.uploaded')),
    'video' => array('type'=>'video','text'=>trans('core::file.type.video'),'title'=>trans('core::file.type.files'),'icon'=>'fas fa-film','route'=>route('media.select.uploaded')),
    'audio' => array('type'=>'audio','text'=>trans('core::file.type.audio'),'title'=>trans('core::file.type.files'),'icon'=>'fas fa-volume-up','route'=>route('media.select.uploaded')),
];

foreach($upload_tools as &$t) {
    
    $filetype = $t['type'];

    $params = [
        'allow'      => config('core.upload.types.'.$filetype.'.extensions'),
        'maxsize'    => config('core.upload.types.'.$filetype.'.extensions'),
        'filetype'   => $filetype,
        'typename'   => config('core.upload.types.'.$filetype.'.extensions'),
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => '',
        'folder'     => '',
        'data_id'    => '',
        'user_id'    => Auth::user()->id,
        'token'      => Auth::user()->token
    ];

    $t['url'] = route('media.select.uploaded', $params); 
}

$other_tools = [];

$tools = array_merge($upload_tools, $other_tools);

debug($args);
debug($tools);

return $tools;
