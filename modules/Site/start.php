<?php
/**
 * 全局导航
 */
\Filter::listen('global.navbar',function($navbar){
    
    // 站点名称
    $navbar['core.sitename'] = [
        'text'   => config('module.site.name'),
        'href'   => route('site.config.base'),
        'class'  => 'sitename',
        'active' => Request::is('*/site/*')
    ];

    return $navbar;
},0);

/**
 * 快捷方式
 */
\Filter::listen('global.start',function($navbar){
  
    //站点设置
    $navbar['config-site'] = [
        'text' => trans('site::config.title'),
        'href' => route('site.config.base'),
        'icon' => 'fa fa-cog bg-success text-white',
        'tips' => trans('site::config.description'),
    ];

    return $navbar;

},99);

/**
 * 全局工具
 */
\Filter::listen('global.tools',function($tools){
        
    // 网站首页
    $tools['viewsite'] = [
        'icon'   => 'fa fa-home',
        'text'   => trans('core::master.viewsite'),
        'title'  => trans('core::master.viewsite.description', [config('module.site.name')]),
        'href'   => config('module.site.url') ?: route('index'),
        'target' => '_blank',
    ];

    return $tools;
},1);
