<?php

namespace Modules\Translator\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Traits\ModuleConfig;
use Modules\Core\Models\Config;

class ConfigController extends AdminController
{
    use ModuleConfig;

    /**
     * 基本配置
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // 保存数据
        if ( $request->isMethod('POST') ) {          

            // 写入配置组
            $this->config('translator', $request->all());

            return $this->success(trans('core::master.saved'), $request->referer());
        }

        $this->title  = trans('translator::config.title');
        $this->config = Config::get('translator');

        $ddd = translate_alias('你好啊，世界大同尽快取餐！');
        debug($ddd);

        return $this->view();
    }  
}
