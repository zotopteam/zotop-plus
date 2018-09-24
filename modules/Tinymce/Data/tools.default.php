<?php
// 上传工具
$upload_tools = [];
$upload_types = config('core.upload.types');

foreach($upload_types as $type=>$config) {
    
    // 如果未开启上传，则不显示按钮
    if (! $config['enabled']) {
        continue;
    }

    // 类型名称
    $typename = trans('core::file.type.'.$type);

    // 上传参数
    $params = [
        'filetype'   => $type,
        'typename'   => $typename,
        'allow'      => $config['extensions'],
        'maxsize'    => $config['maxsize'],
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => array_get($args, 'name'),
        'folder'     => array_get($args, 'folder'),
        'data_id'    => array_get($args, 'data_id'),
        'user_id'    => Auth::user()->id,
        'token'      => Auth::user()->token
    ];

    $upload_tools[$type] = [
        'text'  => $typename,
        'icon' => \File::icon($type),
        'href' => route('media.select.uploaded', $params),
    ];
}

// 其它工具
$other_tools = [];

return array_merge($upload_tools, $other_tools);
