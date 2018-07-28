<?php
namespace Modules\Translator\Hook;

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
        $start['translator'] = [
            'text' => trans('translator::translator.title'),
            'href' => route('translator.config.index'),
            'icon' => 'fa fa-language bg-info text-white',
            'tips' => trans('translator::translator.description'),
        ];
        
        return $start;
    }

    public function module_manage($manage, $module)
    {
        if (strtolower($module) == 'translator') {
            $manage['translator_config'] = [
                'text'  => trans('translator::config.title'),
                'href'  => route('translator.config.index'),
                'icon'  => 'fa fa-cog',
                'class' => '',
            ];
        }
        
        return $manage;        
    } 
}
