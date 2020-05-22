<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

$tools = [];

// 上传工具
$upload_types = config('core.upload.types');

foreach($upload_types as $type=>$config) {
    
    // 如果未开启上传，则不显示按钮
    if (! $config['enabled']) {
        continue;
    }

    // 上传参数
    $params = [
        'type'       => $type,
        'module'     => app('current.module'),
        'controller' => app('current.controller'),
        'action'     => app('current.action'),
        'field'      => Arr::get($args, 'name'),
        'folder'     => Arr::get($args, 'folder'),
        'source_id'  => Arr::get($args, 'source_id'),
        'user_id'    => Auth::user()->id,
        'token'      => Auth::user()->token
    ];

    // 类型名称
    $typename = trans('core::file.type.'.$type);

    // 工具数组
    $tools[$type] = [
        'text'    => trans('tinymce::tinymce.insert.type', [$typename]),
        'icon'    => $type,
        'type'    => $type,
        'title'   => trans('tinymce::tinymce.insert.type', [$typename]),
        'href'    => route('media.select.uploaded', $params),
        'tooltip' => trans('tinymce::tinymce.insert.type', [$typename]),
    ];
}

return $tools;
