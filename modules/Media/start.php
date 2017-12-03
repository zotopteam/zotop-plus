<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.navbar',function($navbar){
    
    $navbar['media'] = [
        'text'   => trans('media::media.title'),
        'href'   => route('media.index'),
        'active' => Route::is('media.*')
    ];

    return $navbar;
});

/**
 * 扩展模块管理
 */
\Filter::listen('global.start',function($navbar){
    
    $navbar['media'] = [
        'text' => trans('media::media.title'),
        'href' => route('media.index'),
        'icon' => 'fa fa-files-o bg-info text-white',
        'tips' => trans('media::media.description'),
    ];
    
    return $navbar;
},80);
