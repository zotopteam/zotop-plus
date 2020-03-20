<?php

namespace Modules\Translator\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\AdminController;
use App\Modules\Traits\ModuleConfig;

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

            // 校验appid和secretkey
            if ($request->engine == 'baidu') {
                $this->validate($request, [
                    'baidu.appid'     => 'required',
                    'baidu.secretkey' => 'required'
                ],[],[
                    'baidu.appid'     => trans('translator::config.baidu.appid'),
                    'baidu.secretkey' => trans('translator::config.baidu.secretkey')
                ]);
            }

            if ($request->engine == 'youdao') {
                $this->validate($request, [
                    'youdao.appid'     => 'required',
                    'youdao.secretkey' => 'required'
                ],[],[
                    'youdao.appid'     => trans('translator::config.youdao.appid'),
                    'youdao.secretkey' => trans('translator::config.youdao.secretkey')
                ]);
            }              

            // 写入配置组
            $this->config('translator', $request->all());

            return $this->success(trans('master.saved'), $request->referer());
        }

        $this->title  = trans('translator::config.title');
        $this->config = config('translator');

        return $this->view();
    }  
}
