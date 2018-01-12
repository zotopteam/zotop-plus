<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.navbar',function($navbar) {

    if (allow('developer.index')) {
        $navbar['developer'] = [
            'text'   => trans('developer::developer.title'),
            'href'   => route('developer.index'),
            'active' => Route::is('developer.*')
        ];
    }
    return $navbar;
}, 80);

/**
 * 扩展模块管理
 */
\Filter::listen('global.start',function($navbar){
    
    if (allow('developer.module.index')) {
        $navbar['developer'] = [
            'text' => trans('developer::module.title'),
            'href' => route('developer.module.index'),
            'icon' => 'fa fa-puzzle-piece bg-warning text-white',
            'tips' => trans('developer::module.description'),
        ];
    }
    
    return $navbar;

}, 80);


/**
 * 安装了开发助手时，一键刷新时发布模块和主题
 */
\Action::listen('system.refresh', function(){
    \Artisan::call('module:publish');
    \Artisan::call('theme:publish');
});
