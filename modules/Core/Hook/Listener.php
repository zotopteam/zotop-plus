<?php
namespace Modules\Core\Hook;

use Route;


class Listener
{
    /**
     * 后台开始菜单扩展
     * @param  array $start 已有开始菜单
     * @return array
     */
    public function start($start)
    {
        //编辑我的资料
        $start['mine-edit'] = [
            'text' => trans('core::mine.edit'),
            'href' => route('core.mine.edit'),
            'icon' => 'fa fa-user-circle bg-primary text-white', 
            'tips' => trans('core::mine.edit.description'),
        ];

        //修改我的密码
        $start['mine-password'] = [
            'text' => trans('core::mine.password'),
            'href' => route('core.mine.password'),
            'icon' => 'fa fa-key bg-primary text-white', 
            'tips' => trans('core::mine.password.description'),
        ];

        //管理员快捷方式
        $start['administrator'] = [
            'text' => trans('core::administrator.title'),
            'href' => route('core.administrator.index'),
            'icon' => 'fa fa-users bg-primary text-white', 
            'tips' => trans('core::administrator.description'),
        ];

        //管理员快捷方式
        $start['core-config'] = [
            'text' => trans('core::config.title'),
            'href' => route('core.config.index'),
            'icon' => 'fa fa-cogs bg-primary text-white', 
            'tips' => trans('core::config.description'),
        ];    

        //模块管理
        $start['themes'] = [
            'text' => trans('core::themes.title'),
            'href' => route('core.themes.index'),
            'icon' => 'fa fa-gem bg-primary text-white', 
            'tips' => trans('core::themes.description'),
        ];
          
        //模块管理
        $start['modules'] = [
            'text' => trans('core::modules.title'),
            'href' => route('core.modules.index'),
            'icon' => 'fa fa-puzzle-piece bg-primary text-white', 
            'tips' => trans('core::modules.description'),
        ];

        //environment 服务器环境
        $start['environment'] = [
            'text' => trans('core::system.environment.title'),
            'href' => route('core.system.environment'),
            'icon' => 'fa fa-server bg-primary text-white', 
            'tips' => trans('core::system.environment.description'),
        ];

        $start['about'] = [
            'text' => trans('core::system.about.title'),
            'href' => route('core.system.about'),
            'icon' => 'fa fa-info-circle bg-primary text-white', 
            'tips' => trans('core::system.about.description'),
        ];        
            
        return $start;
    }

    /**
     * 后台快捷导航扩展
     * @param  array $start 已有快捷导航
     * @return array
     */
    public function navbar($navbar)
    {
        // 主页
        $navbar['core.index'] = [
            'text'   => trans('core::master.index'),
            'href'   => route('admin.index'),
            'class'  => 'index', 
            'active' => Route::is('admin.index')
        ];
        return $navbar;
    }

    /**
     * 后台快捷工具扩展
     * @param  array $start 已有快捷工具
     * @return array
     */
    public function tools($tools)
    {
        // 一键刷新
        $tools['refresh'] = [
            'icon'     => 'fa fa-magic', 
            'text'     => trans('core::master.refresh'),
            'title'    => trans('core::master.refresh.description'),
            'href'     => 'javascript:;',
            'data-url' => route('core.system.refresh'),
            'class'    => 'refresh js-post',
        ];
        return $tools;
    }
}
