<?php
namespace Modules\Core\Hook;

use Route;
use Modules\Core\Support\Resize;
use Modules\Core\Support\Watermark;

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
        if (allow('core.themes.index')) {
            $start['themes'] = [
                'text' => trans('core::themes.title'),
                'href' => route('core.themes.index'),
                'icon' => 'fa fa-gem bg-primary text-white', 
                'tips' => trans('core::themes.description'),
            ];
        }
          
        //模块管理
        if (allow('core.modules.index')) {
            $start['modules'] = [
                'text' => trans('core::modules.title'),
                'href' => route('core.modules.index'),
                'icon' => 'fa fa-puzzle-piece bg-primary text-white', 
                'tips' => trans('core::modules.description'),
            ];
        }

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
        if (allow('core.system.refresh')) {
            $tools['refresh'] = [
                'icon'     => 'fa fa-magic', 
                'text'     => trans('core::system.fastrefresh'),
                'title'    => trans('core::system.fastrefresh.tips'),
                'href'     => 'javascript:;',
                'data-url' => route('core.system.refresh',['fast']),
                'class'    => 'fastclean js-post',
            ];
        }
        
        return $tools;
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
}
