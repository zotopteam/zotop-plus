<?php
\Filter::listen('global.start',function($navbar){
    
    //区域管理
    $navbar['region.manage'] = [
        'text' => trans('region::module.title'),
        'href' => route('region.index'),
        'icon' => 'fa fa-map-marker bg-success text-white',
        'tips' => trans('region::module.description'),
    ];
    
    return $navbar;
    
});