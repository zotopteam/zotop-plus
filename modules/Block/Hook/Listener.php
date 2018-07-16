<?php
namespace Modules\Block\Hook;

use Route;
use Auth;


class Listener
{
    /**
     * 开始菜单扩展
     * 
     * @param  array $start 开始菜单数组
     * @return array
     */
    public function start($start)
    {
        $start['block'] = [
            'text' => trans('block::block.title'),
            'href' => route('block.index'),
            'icon' => 'fa fa-cubes bg-info text-white',
            'tips' => trans('block::block.about'),
        ];
        
        return $start;
    }

    public function navbar($navbar)
    {
        $navbar['block'] = [
            'text'   => trans('block::block.title'),
            'href'   => route('block.index'),
            'active' => Route::is('block.*')

        ];

        return $navbar;
    }

}
