<?php
$manages = [];

// 重启管理
$manages['reboot'] = [
    'icon'        => 'fa fa-sync text-danger',
    'title'       => trans('core::system.reboot.title'),
    'description' => trans('core::system.reboot.description'),
    'action'      => ['text'=>trans('core::system.reboot'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-sync', 'href'=>route('core.system.manage',['artisan'=>'reboot'])]
];

// 缓存管理
$manages['config-cache'] = [
    'icon'        => 'fa fa-cogs text-primary',
    'title'       => trans('core::system.config.cache.title'),
    'description' => trans('core::system.config.cache.description'),
    'action'      => app()->configurationIsCached() ?
                    ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'config:clear'])] :
                    ['text'=>trans('master.cache'), 'class' => 'btn btn-success js-post', 'icon'=>'fa fa-dot-circle', 'href'=>route('core.system.manage',['artisan'=>'config:cache'])]
];

// 缓存管理
$manages['route-cache'] = [
    'icon'        => 'fa fa-sitemap text-primary',
    'title'       => trans('core::system.route.cache.title'),
    'description' => trans('core::system.route.cache.description'),
    'action'      => app()->routesAreCached() ?
                    ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'route:clear'])] :
                    ['text'=>trans('master.cache'), 'class' => 'btn btn-success js-post', 'icon'=>'fa fa-dot-circle', 'href'=>route('core.system.manage',['artisan'=>'route:cache'])]
];

// 上传临时文件
$manages['plupload-clear'] = [
    'icon'        => 'fa fa-upload text-info',
    'title'       => trans('core::system.plupload.clear.title'),
    'description' => trans('core::system.plupload.clear.description'),
    'directory'   => 'storage/plupload',
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'plupload:clear'])]
];

// 日志清理
$manages['debugbar-clear'] = [
    'icon'        => 'fa fa-bug text-info',
    'title'       => trans('core::system.debugbar.clear.title'),
    'description' => trans('core::system.debugbar.clear.description'),
    'directory'   => 'storage/debugbar',
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'debugbar:clear'])]
]; 

// 日志清理
$manages['log-clear'] = [
    'icon'        => 'fa fa-history text-info',
    'title'       => trans('core::system.log.clear.title'),
    'description' => trans('core::system.log.clear.description'),
    'directory'   => 'storage/logs',
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'log:clear'])]
]; 

// 缩略图清理
$manages['thumbnail-clear'] = [
    'icon'        => 'fa fa-images text-warning',
    'title'       => trans('core::system.thumbnail.clear.title'),
    'description' => trans('core::system.thumbnail.clear.description'),
    'directory'   => 'public/thumbnails',
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'thumbnail:clear'])]
];

// 预览图清理
$manages['preview-clear'] = [
    'icon'        => 'fa fa-images text-warning',
    'title'       => trans('core::system.preview.clear.title'),
    'description' => trans('core::system.preview.clear.description'),
    'directory'   => 'public/previews',
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'preview:clear'])]
];

// 视图缓存清理
$manages['view-clear'] = [
    'icon'        => 'fa fa-eye text-info',
    'title'       => trans('core::system.view.clear.title'),
    'description' => trans('core::system.view.clear.description'),
    'action'      => ['text'=>trans('master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-recycle', 'href'=>route('core.system.manage',['artisan'=>'view:clear'])]
];

return $manages;
