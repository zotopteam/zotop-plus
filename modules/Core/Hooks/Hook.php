<?php
namespace Modules\Core\Hooks;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Core\Support\Resize;
use Modules\Core\Support\Watermark;

class Hook
{
    /**
     * 后台开始菜单扩展
     * @param  array $start 已有开始菜单
     * @return array
     */
    public function start($start)
    {
        //编辑我的资料
        if (allow('core.mine.edit')) {
            $start['mine-edit'] = [
                'text'  => trans('core::mine.edit'),
                'href'  => route('core.mine.edit'),
                'icon'  => 'fa fa-user-circle bg-primary text-white', 
                'tips'  => trans('core::mine.edit.description'),
            ];
        }

        //修改我的密码
        if (allow('core.mine.password')) {
            $start['mine-password'] = [
                'text' => trans('core::mine.password'),
                'href' => route('core.mine.password'),
                'icon' => 'fa fa-key bg-primary text-white', 
                'tips' => trans('core::mine.password.description'),
            ];
        }

        //管理员快捷方式
        if (allow('core.administrator.index')) {
            $start['administrator'] = [
                'text' => trans('core::administrator.title'),
                'href' => route('core.administrator.index'),
                'icon' => 'fa fa-users bg-primary text-white', 
                'tips' => trans('core::administrator.description'),
            ];
        }

        //系统设置
        if (allow('core.config.index')) {
            $start['core-config'] = [
                'text' => trans('core::config.title'),
                'href' => route('core.config.index'),
                'icon' => 'fa fa-cogs bg-primary text-white', 
                'tips' => trans('core::config.description'),
            ];
        }

        // 主题管理
        if (allow('core.theme.index')) {
            $start['core-themes'] = [
                'text' => trans('core::theme.title'),
                'href' => route('core.theme.index'),
                'icon' => 'fa fa-tshirt bg-primary text-white', 
                'tips' => trans('core::theme.description'),
            ];
        }
          
        //模块管理
        if (allow('core.module.index')) {
            $start['core-modules'] = [
                'text' => trans('core::module.title'),
                'href' => route('core.module.index'),
                'icon' => 'fa fa-puzzle-piece bg-primary text-white', 
                'tips' => trans('core::module.description'),
            ];
        }

        //计划任务
        if (allow('core.scheduling.index')) {
            $start['core-scheduling'] = [
                'text' => trans('core::scheduling.title'),
                'href' => route('core.scheduling.index'),
                'icon' => 'fa fa-clock bg-primary text-white', 
                'tips' => trans('core::scheduling.description'),
            ];
        }        

        //系统管理
        if (allow('core.system.manage')) {
            $start['core-manage'] = [
                'text' => trans('core::system.manage.title'),
                'href' => route('core.system.manage'),
                'icon' => 'fa fa-globe bg-primary text-white', 
                'tips' => trans('core::system.manage.description'),
            ];
        }
        
        //log 操作日志
        if (allow('core.log.index')) {
            $start['core-log'] = [
                'text' => trans('core::log.title'),
                'href' => route('core.log.index'),
                'icon' => 'fa fa-clipboard-list bg-primary text-white', 
                'tips' => trans('core::log.description'),
            ];
        }

        //environment 服务器环境
        if (allow('core.system.environment')) {
            $start['core-environment'] = [
                'text' => trans('core::system.environment.title'),
                'href' => route('core.system.environment'),
                'icon' => 'fa fa-server bg-primary text-white', 
                'tips' => trans('core::system.environment.description'),
            ];
        }

        $start['core-about'] = [
            'text' => trans('core::system.about.title'),
            'href' => route('core.system.about'),
            'icon' => 'fa fa-info-circle bg-primary text-white', 
            'tips' => trans('core::system.about.description'),
        ];        
            
        return $start;
    }

    /**
     * 后台快捷导航扩展
     * @param  array $navabr 已有快捷导航
     * @return array
     */
    public function navbar($navbar)
    {
        // 主页
        $navbar['core.index'] = [
            'text'   => trans('core::core.index'),
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
        // 消息通知
        $tools['notification'] = [
            'icon'        => 'fa fa-bell', 
            'title'       => trans('core::notification.title'),
            'href'        => route('core.notifications.index'),
            'badge'       => 0,
            'class'       => 'global-notification js-open',
            'data-width'  => '80%',
            'data-height' => '70%',
        ];

        // 重启系统
        if (allow('core.system.manage')) {
            $tools['reboot'] = [
                'icon'  => 'fa fa-sync', 
                'title' => trans('core::system.reboot.title'),
                'href'  => route('core.system.manage', ['artisan'=>'reboot']),
                'class' => 'js-post',
            ];
        }
        
        return $tools;
    }

    /**
     * 后台快捷工具扩展
     * @param  array $start 已有快捷工具
     * @return array
     */
    public function windowCms($cms)
    {
        $cms['environment']  = App::environment();
        $cms['user_id']      = Auth::id() ?? 0;
        $cms['notification'] = [];

        // 自动检查通知信息
        if (config('core.notification.check.enabled', 1)) {
            $cms['notification']['check'] = [
                'url'      => route('core.notifications.check'),
                'interval' => config('core.notification.check.interval', 60), //单位：秒
            ];
        }

        return $cms;
    }

    /**
     * 模块管理按钮
     * 
     * @param  array $manage 按钮数组
     * @param  module $module 模块对象
     * @return array
     */
    public function moduleManage($manage, $module)
    {
        if ($module->isInstalled()) {

            // 禁用和启用
            if($module->isEnabled()) {
                $manage['disable'] = [
                    'text'     => trans('master.disable'),
                    'data-url' => route('core.module.disable',[$module->name]),
                    'icon'     => 'fa fa-times-circle',
                    'class'    => 'js-confirm',
                ];              
            } else {
                $manage['active'] = [
                    'text'     => trans('master.active'),
                    'data-url' => route('core.module.enable',[$module->name]),
                    'icon'     => 'fa fa-check-circle ',
                    'class'    => 'js-confirm',
                ];                 
            }

            // 卸载
            $manage['uninstall'] = [
                'text'         => trans('core::module.uninstall'),
                'data-url'     => route('core.module.uninstall',[$module->name]),
                'data-confirm' => trans('core::module.uninstall.confirm', [$module->getTitle()]),
                'icon'         => 'fa fa-trash ',
                'class'        => 'js-confirm',
            ];              

        } else {
            // 安装
            $manage['install'] = [
                'text'     => trans('core::module.install'),
                'data-url' => route('core.module.install',[$module->name]),
                'icon'     => 'fa fa-wrench',
                'class'    => 'js-confirm',
            ];

            // 删除
            $manage['delete'] = [
                'text'         => trans('core::module.delete'),
                'data-url'     => route('core.module.delete',[$module->name]),
                'data-confirm' => trans('core::module.delete.confirm', [$module->getTitle()]),
                'icon'         => 'fa fa-times',
                'class'        => 'js-confirm',
            ];                        
        }

        return $manage;
    }

    /**
     * 核心模块禁止 禁用和卸载
     * 
     * @param  array $manage 按钮数组
     * @param  module $module 模块对象
     * @return array
     */
    public function moduleManageCore($manage, $module)
    {
        // 核心模块禁止卸载和禁用
        if ($module->is('core') && $module->isInstalled()) {
            Arr::forget($manage, ['disable','uninstall']);
            $manage = Arr::prepend($manage, [
                'text'  => trans('core::config.title'),
                'href'  => route('core.config.index'),
                'icon'  => 'fa fa-cog',
                'class' => '',
            ], 'core_config');
        }

        return $manage;
    }
}
