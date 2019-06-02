<?php
namespace Modules\Core\Hook;

use App;
use Route;
use Modules\Core\Support\Resize;
use Modules\Core\Support\Watermark;
use Auth;

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
                'icon' => 'fa fa-gem bg-primary text-white', 
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

        //environment 服务器环境
        if (allow('core.system.manage')) {
            $start['core-manage'] = [
                'text' => trans('core::system.manage.title'),
                'href' => route('core.system.manage'),
                'icon' => 'fa fa-server bg-primary text-white', 
                'tips' => trans('core::system.manage.description'),
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
        $tools['notification'] = [
            'icon'  => 'fa fa-bell', 
            //'text'  => trans('core::notification.short'),
            'title' => trans('core::notification.title'),
            'href'  => route('core.notifications.index'),
            'badge' => 0,
            'class' => 'global-notification'
        ];

        // 一键刷新
        if (allow('core.system.manage')) {
            $tools['reboot'] = [
                'icon'  => 'fa fa-sync', 
                //'text'  => trans('core::system.reboot'),
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
        $cms['user_id']      = intval(Auth::id());
        $cms['notification'] = [
            'check'    => route('core.notifications.check'),
            'interval' => 30, //单位：秒
        ]; 
        return $cms;
    }    

    /**
     * 监听上传
     * @param  array $return  返回给前端的文件信息
     * @param  object $splFile 文件
     * @param  array $params  参数
     * @return array
     */
    public function upload($return, $splFile, $params)
    {
        // 处理图片 TODO：使用队列处理
        if ($return['type']=='image') {
            
            // 图片路径
            $path = $splFile->getRealPath();

            try {

                // 图片缩放
                app(Resize::class)->with($params['resize'] ?? [])->apply($path);

                // 图片水印
                app(Watermark::class)->with($params['watermark'] ?? [])->apply($path);

                // 获取宽高和大小
                $image = app('image')->make($path);

                $return['size']   = $image->filesize();
                $return['width']  = $image->width();
                $return['height'] = $image->height();               

            } catch (Exception $e) {
                return ['state'=>false, 'content'=>$e->getMessage()];
            }       
        }

        return $return;
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
        if ($module->installed) {

            // 禁用和启用
            if($module->active) {
                $manage['disable'] = [
                    'text'     => trans('core::master.disable'),
                    'data-url' => route('core.module.disable',[$module->name]),
                    'icon'     => 'fa fa-times-circle',
                    'class'    => 'js-confirm',
                ];              
            } else {
                $manage['active'] = [
                    'text'     => trans('core::master.active'),
                    'data-url' => route('core.module.enable',[$module->name]),
                    'icon'     => 'fa fa-check-circle ',
                    'class'    => 'js-confirm',
                ];                 
            }

            // 卸载
            $manage['uninstall'] = [
                'text'         => trans('core::module.uninstall'),
                'data-url'     => route('core.module.uninstall',[$module->name]),
                'data-confirm' => trans('core::module.uninstall.confirm', [$module->title]),
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
                'data-confirm' => trans('core::module.delete.confirm', [$module->title]),
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
        if (in_array(strtolower($module), config('modules.cores', ['core']))) {

            // 当模块安装后，禁止和卸载按钮 禁用状态
            if ($module->installed) {
                $manage['disable'] = [
                    'text'  => trans('core::master.disable'),
                    'icon'  => 'fa fa-times-circle',
                    'class' => 'disabled',
                ];
                $manage['uninstall'] = [
                    'text'  => trans('core::module.uninstall'),
                    'icon'  => 'fa fa-trash ',
                    'class' => 'disabled',
                ];
            }

        }

        return $manage;
    }
}
