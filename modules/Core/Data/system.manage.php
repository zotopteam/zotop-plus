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
    'action'      => File::exists(app()->bootstrapPath().'/cache/config.php') ?
                    ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times-circle', 'href'=>route('core.system.manage',['artisan'=>'config:clear'])] :
                    ['text'=>trans('core::master.cache'), 'class' => 'btn btn-success js-post', 'icon'=>'fa fa-dot-circle', 'href'=>route('core.system.manage',['artisan'=>'config:cache'])]
];

// 缓存管理
$manages['route-cache'] = [
    'icon'        => 'fa fa-sitemap text-primary',
    'title'       => trans('core::system.route.cache.title'),
    'description' => trans('core::system.route.cache.description'),
    'action'      => File::exists(app()->bootstrapPath().'/cache/routes.php') ?
                    ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times-circle', 'href'=>route('core.system.manage',['artisan'=>'route:clear'])] :
                    ['text'=>trans('core::master.cache'), 'class' => 'btn btn-success js-post', 'icon'=>'fa fa-dot-circle', 'href'=>route('core.system.manage',['artisan'=>'route:cache'])]
];

// 缩略图清理
$manages['thumbnail-clear'] = [
    'icon'        => 'fa fa-images text-warning',
    'title'       => trans('core::system.thumbnail.clear.title'),
    'description' => trans('core::system.thumbnail.clear.description'),
    'action'      => ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times', 'href'=>route('core.system.manage',['artisan'=>'thumbnail:clear'])]
];

// 预览图清理
$manages['preview-clear'] = [
    'icon'        => 'fa fa-images text-warning',
    'title'       => trans('core::system.preview.clear.title'),
    'description' => trans('core::system.preview.clear.description'),
    'action'      => ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times', 'href'=>route('core.system.manage',['artisan'=>'preview:clear'])]
];

// 日志清理
$manages['log-clear'] = [
    'icon'        => 'fa fa-history text-info',
    'title'       => trans('core::system.log.clear.title'),
    'description' => trans('core::system.log.clear.description'),
    'action'      => ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times', 'href'=>route('core.system.manage',['artisan'=>'log:clear'])]
];             

// 视图缓存清理
$manages['view-clear'] = [
    'icon'        => 'fa fa-eye text-info',
    'title'       => trans('core::system.view.clear.title'),
    'description' => trans('core::system.view.clear.description'),
    'action'      => ['text'=>trans('core::master.clear'), 'class' => 'btn btn-danger js-post', 'icon'=>'fa fa-times', 'href'=>route('core.system.manage',['artisan'=>'view:clear'])]
];

return $manages;
