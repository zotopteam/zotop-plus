<?php
/*
 * 模型自动包含文件
 */

/**
 * 扩展后台全局导航
 */
\Filter::listen('global.navbar',function($navbar){
    
    $navbar['developer'] = [
        'text'   => trans('developer::module.title'),
        'href'   => route('developer.index'),
        'active' => Request::is('*/developer/*')
    ];

    return $navbar;
});

/**
 * 扩展模块管理
 */
\Filter::listen('global.start',function($navbar){
    
    $navbar['developer'] = [
        'text' => trans('developer::module.develop'),
        'href' => route('developer.module.index'),
        'icon' => 'fa fa-puzzle-piece bg-warning text-white',
        'tips' => trans('developer::module.develop.description'),
    ];
    
    return $navbar;
},80);