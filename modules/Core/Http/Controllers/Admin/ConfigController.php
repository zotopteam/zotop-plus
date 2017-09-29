<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Traits\ModuleConfig;
use Modules\Core\Http\Requests\ConfigBaseRequest;
use Filter;
use Artisan;
use Route;

class ConfigController extends AdminController
{
    use ModuleConfig;

    /**
     * 初始化
     * 
     * @return null
     */
    public function __construct()
    {
        // 初始化父类
        parent::__construct();

        //  初始化侧边导航条
        $this->navbar = Filter::fire('core.config.navbar', [
            'upload' => [
                'text'   => trans('core::config.upload'),
                'href'   => route('core.config.upload'),
                'class'  => 'fa fa-fw fa-upload',
                'active' => Route::is('core.config.upload')
            ],
            'mail'   => [
                'text'   => trans('core::config.mail'),
                'href'   => route('core.config.mail'),
                'class'  => 'fa fa-fw fa-envelope',
                'active' => Route::is('core.config.mail'),
            ],
            'locale'   => [
                'text'   => trans('core::config.locale'),
                'href'   => route('core.config.locale'),
                'class'  => 'fa fa-fw fa-map',
                'active' => Route::is('core.config.locale'),
            ],            
            'safe'   => [
                'text'   => trans('core::config.safe'),
                'href'   => route('core.config.safe'),
                'class'  => 'fa fa-fw fa-shield',
                'active' => Route::is('core.config.safe')
            ],
        ]);
    }

    /**
     * 配置首页
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return redirect()->route('core.config.upload');
    }


    /**
     * 文件上传
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            // 写入配置组
            $this->save('modules.core.upload', $request->all());

            return $this->success(trans('core::master.saved'));
        }

        $this->title = trans('core::config.upload');

        return $this->view();
    }

    /**
     * 邮件
     *
     * @return Response
     */
    public function mail(Request $request)
    {
        $this->title = trans('core::config.mail');

        return $this->view();
    }  

    /**
     * 本地时区和时间设置
     *
     * @return Response
     */
    public function locale(Request $request)
    {
        $this->title = trans('core::config.locale');

        $timezones = [];

        foreach(timezone_identifiers_list() as $key => $zone) {
            $timezones[$zone] = 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone($zone)))->format('P').' - '.$zone;    
        }

        dd($timezones);

        return $this->view();
    }  

    /**
     * 系统安全
     *
     * @return Response
     */
    public function safe(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            // 写入ENV配置
            $this->setEnv([
                'APP_ENV'          => $request->input('env', 'production'),
                'APP_DEBUG'        => $request->input('debug', 'production') ? 'true' : 'false',
                //'APP_KEY'          => $request->input('key'), //TODO: 未知问题，无法保存
                'APP_ADMIN_PREFIX' => $request->input('admin_prefix', 'admin'),
                'APP_LOG'          => $request->input('log', 'single'),
                'APP_LOG_LEVEL'    => $request->input('log_level', 'debug'),
            ]);

            // 更改后台地址，TODO：本地或者测试环境下，route 已经加载，无法重新载入
            config([
                'app.admin_prefix' => $request->input('admin_prefix', 'admin')
            ]);

            return $this->success(trans('core::master.saved'), route('core.config.safe'));
        }

        $this->title = trans('core::config.safe');

        // 运行环境选项
        $this->envs = Filter::fire('core.config.envs' ,[
            'production' => trans('core::config.envs.production'),
            'local'      => trans('core::config.envs.local'),
            'testing'    => trans('core::config.envs.testing'),
        ]);

        // 日志模式选项
        $this->logs = array_combine(
            ['single','daily','syslog','errorlog'],
            ['single','daily','syslog','errorlog']
        );

        // 日志级别选项
        $this->log_levels = array_combine(
            ['debug','info','notice','warning','error','critical','alert','emergency'],
            ['debug','info','notice','warning','error','critical','alert','emergency']
        );

        return $this->view();
    }         
}
