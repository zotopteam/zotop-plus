<?php

namespace Modules\Developer\Hooks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Hook
{
    /**
     * Hook the start
     * @param  array $start
     * @return array
     */
    public function start($start)
    {
        // 无权限或者非本地模式下不显示
        if (allow('developer.module') && app()->environment('local')) {
            $start['developer-module'] = [
                'text' => trans('developer::module.title'),
                'href' => route('developer.module.index'),
                'icon' => 'fa fa-puzzle-piece bg-warning text-white',
                'tips' => trans('developer::module.description'),
            ];
            $start['developer-theme'] = [
                'text' => trans('developer::theme.title'),
                'href' => route('developer.theme.index'),
                'icon' => 'fa fa-gem bg-warning text-white',
                'tips' => trans('developer::theme.description'),
            ];
            $start['developer-route'] = [
                'text' => trans('developer::route.title'),
                'href' => route('developer.route.index'),
                'icon' => 'fa fa-link bg-warning text-white',
                'tips' => trans('developer::route.description'),
            ];                        
        }

        return $start;
    }

    /**
     * Hook the navbar
     * @param  array $navbar
     * @return array
     */
    public function navbar($navbar)
    {
        // 只在本地模式下不显示
        if (allow('developer.index') && app()->environment('local')) {
            $navbar['developer'] = [
                'text'   => trans('developer::developer.title'),
                'href'   => route('developer.index'),
                'active' => Route::is('developer.*')
            ];
        }
               
        return $navbar;
    }

    /**
     * Hook the tools
     * @param  array $navbar
     * @return array
     */
    public function tools($tools)
    {
        // 只在本地模式下不显示
        if (allow('developer.index') && app()->environment('local')) {
            $tools['developer'] = [
                'title'   => trans('developer::developer.title'),
                'href'   => route('developer.index'),
                'icon'   => 'fa fa-tools',
                'class' => 'text-warning',
            ];
        }
               
        return $tools;
    }    
}
