<?php

namespace Modules\Site\Http\Controllers\Admin;

use App\Modules\Routing\AdminController;
use App\Modules\Traits\ModuleConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class ConfigController extends AdminController
{
    use ModuleConfig;

    /**
     * 基本配置
     *
     * @return Response
     */
    public function base(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 表单验证
            $this->validate($request, [
                'name' => 'required',
                'url'  => 'url|nullable'
            ],[],[
                'name' => trans('site::config.name.label'),
                'url'  => trans('site::config.url.label')
            ]);           

            // 写入配置组
            $this->config('site', $request->all());

            // 发布保存的主题资源
            Artisan::call('theme:publish', [
                'theme' => $request->input('theme')
            ]);

            return $this->success(trans('master.saved'), $request->referer());
        }

        $this->title  = trans('site::config.base');
        $this->config = config('site');

        return $this->view();
    }
    /**
     * 基本配置
     *
     * @return Response
     */
    public function wap(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 表单验证
            $this->validate($request, [
                'wap.url'  => 'url|nullable'
            ],[],[
                'url'  => trans('site::config.wap.url.label')
            ]);           

            // 写入配置组
            $this->config('site', $request->all());

            // 发布保存的主题资源
            Artisan::call('theme:publish', [
                'theme' => $request->input('wap.theme')
            ]);

            return $this->success(trans('master.saved'), $request->referer());
        }

        $this->title  = trans('site::config.wap');
        $this->config = config('site');

        return $this->view();
    }
    /**
     * 搜索优化
     *
     * @return Response
     */
    public function seo(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 写入配置组
            $this->config('site', $request->all());

            return $this->success(trans('master.saved'));
        }

        $this->title  = trans('site::config.seo');
        $this->config = config('site');

        return $this->view();
    }

    /**
     * 维护模式
     *
     * @return Response
     */
    public function maintain(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            $config = $request->all();
            $config = $config + ['maintained'=>0];
            
            // 写入配置组
            $this->config('site', $config);

            return $this->success(trans('master.saved'));
        }

        $this->title  = trans('site::config.maintain');
        $this->config = config('site');

        return $this->view();
    }     
}
