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
}
