<?php
\Filter::listen('global.start',function($navbar){
    
    //区域管理
    $navbar['region.manage'] = [
        'text' => trans('region::region.title'),
        'href' => route('region.index'),
        'icon' => 'fa fa-map-marker bg-success text-white',
        'tips' => trans('region::region.description'),
    ];
    
    return $navbar;
    
});
