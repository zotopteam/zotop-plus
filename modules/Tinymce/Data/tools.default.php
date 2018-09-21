<?php
$upload_tools = [
    'image' => array('type'=>'image','icon'=>'image','text'=>trans('core::file.type.image'),'title'=>trans('core::file.type.image'),'url'=>route('media.select.uploaded')),
    'files' => array('type'=>'files','icon'=>'fas fa-file','text'=>trans('core::file.type.files'),'title'=>trans('core::file.type.files'),'url'=>route('media.select.uploaded')),
    'video' => array('type'=>'video','icon'=>'fas fa-film','text'=>trans('core::file.type.video'),'title'=>trans('core::file.type.files'),'url'=>route('media.select.uploaded')),
    'audio' => array('type'=>'audio','icon'=>'fas fa-volume-up','text'=>trans('core::file.type.audio'),'title'=>trans('core::file.type.files'),'url'=>route('media.select.uploaded')),
];

foreach($upload_tools as &$t) {
    
    $filetype = $t['type'];

    $params = [
        'filetype'   => $filetype,
        'typename'   => trans('core::file.type.'.$filetype),
        'allow'      => config('core.upload.types.'.$filetype.'.extensions'),
        'maxsize'    => config('core.upload.types.'.$filetype.'.maxsize'),
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => array_get($args, 'name'),
        'folder'     => array_get($args, 'folder'),
        'data_id'    => array_get($args, 'data_id'),
        'user_id'    => Auth::user()->id,
        'token'      => Auth::user()->token
    ];

    $t['url'] = route('media.select.uploaded', $params);
}

$other_tools = [];

$tools = array_merge($upload_tools, $other_tools);

return $tools;
