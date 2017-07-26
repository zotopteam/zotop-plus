<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Traits\ModuleConfig;
use Modules\Core\Http\Requests\ConfigBaseRequest;

class ConfigController extends AdminController
{
    use ModuleConfig;

    /**
     * 基本配置
     *
     * @return Response
     */
    public function base(ConfigBaseRequest $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 写入配置组
            $this->save('cms.modules.core.site', $request->all());

            return $this->success(trans('core::master.saved'),$request->referer());
        }

        $this->title = trans('core::config.site.base');

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
            $this->save('cms.modules.core.site', $request->all());

            return $this->success(trans('core::master.saved'));
        }

        $this->title = trans('core::config.site.seo');

        return $this->view();
    }

    /**
     * 文件上传
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {

            // 写入配置组
            $this->save('cms.modules.core.upload', $request->all());

            return $this->success(trans('core::master.saved'));
        }

        $this->title = trans('core::config.core.upload');

        return $this->view();
    }      
}
