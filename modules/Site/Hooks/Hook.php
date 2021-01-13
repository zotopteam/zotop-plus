<?php

namespace Modules\Site\Hooks;

use App\Modules\Module;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class Hook
{
    /**
     * Hook the start
     *
     * @param array $start
     * @return array
     */
    public function start(array $start)
    {
        if (allow('site.config.base')) {
            $start = Arr::prepend($start, [
                'text' => trans('site::config.title'),
                'href' => route('site.config.base'),
                'icon' => 'fa fa-cog bg-success text-white',
                'tips' => trans('site::config.description'),
            ], 'site_config');
        }
        
        return $start;
    }

    /**
     * Hook the navbar
     *
     * @param array $navbar
     * @return array
     */
    public function navbar(array $navbar)
    {
        // 在导航条最开始追加站点名称
        $navbar = Arr::prepend($navbar, [
            'text'   => config('site.name'),
            'href'   => route('site.config.base'),
            'class'  => 'site-name',
            'active' => Route::is('site.*'),
        ], 'site_name');

        return $navbar;
    }

    /**
     * Hook the tools
     *
     * @param array $tools
     * @return array
     */
    public function tools(array $tools)
    {
        // 在导航条最开始追加站点名称
        $tools = Arr::prepend($tools, [
            'icon'   => 'fa fa-home',
            //'text'   => trans('site::site.view'),
            'title'  => trans('site::site.view.tips', [config('site.name')]),
            'href'   => config('site.url') ?: route('index'),
            'target' => '_blank',
        ], 'site_view');

        return $tools;
    }

    /**
     * 站点模块禁止 禁用和卸载
     *
     * @param array $manage 按钮数组
     * @param \App\Modules\Module $module 模块对象
     * @return array
     */
    public function moduleManageSite(array $manage, Module $module)
    {
        // 核心模块禁止卸载和禁用
        if ($module->is('site') && $module->isInstalled()) {
            Arr::forget($manage, ['disable', 'uninstall']);
            $manage = Arr::prepend($manage, [
                'text' => trans('site::config.title'),
                'href' => route('site.config.base'),
                'icon' => 'fa fa-cog',
            ], 'site_config');
        }

        return $manage;
    }
}
