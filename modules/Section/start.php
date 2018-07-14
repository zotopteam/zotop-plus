<?php
\Filter::listen('global.start',function($navbar){
    
    //区域管理
    $navbar['section.manage'] = [
        'text' => trans('section::section.title'),
        'href' => route('section.index'),
        'icon' => 'fa fa-cubes bg-info text-white',
        'tips' => trans('section::section.description'),
    ];
    
    return $navbar;
    
});
