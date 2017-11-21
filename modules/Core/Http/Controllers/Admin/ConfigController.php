<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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

        $this->title  = trans('core::config.upload');
        $this->config = config('core');

        return $this->view();
    }

    /**
     * 邮件
     *
     * @return Response
     */
    public function mail(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            // 写入ENV配置
            $this->setEnv([
                'MAIL_DRIVER'       => $request->input('driver'),
                'MAIL_FROM_ADDRESS' => $request->input('from.address'),
                'MAIL_FROM_NAME'    => $request->input('from.name'),                
                'MAIL_HOST'         => $request->input('host'),
                'MAIL_PORT'         => $request->input('port'),
                'MAIL_USERNAME'     => $request->input('username'),
                'MAIL_PASSWORD'     => $request->input('password'),
                'MAIL_ENCRYPTION'   => $request->input('encryption'),
            ]);

            return $this->success(trans('core::master.saved'));
        }

        $this->title = trans('core::config.mail');
        $this->drivers = Filter::fire('core.config.mail.drivers' ,[
            'smtp'     => trans('core::config.mail.drivers.smtp'),
            'mail'     => trans('core::config.mail.drivers.mail'),
            'sendmail' => trans('core::config.mail.drivers.sendmail'),
        ]);

        return $this->view();
    }

    public function mailtest(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            if ($to = $request->input('mailtest_sendto')) {

                // 设置当前参数
                $this->app['config']->set('mail',$request->all());

                // 使用新配置
                (new \Illuminate\Mail\MailServiceProvider($this->app))->register();

                // 发送邮件
                try {
                    $this->app->make('mailer')->to($to)->send(new \Modules\Core\Emails\TestMail());
                    $msg = $this->success(trans('core::master.operated'));                    
                } catch (\Swift_TransportException $e) {
                    $msg = $this->error($e->getMessage());
                }
                
                exit($msg->getContent());                              
            }

            return $this->error(trans('core::master.forbidden'));
        }

        return new \Modules\Core\Emails\TestMail();
    }

    /**
     * 本地时区和时间设置
     *
     * @return Response
     */
    public function locale(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            // 写入ENV配置
            $this->setEnv([
                'APP_LOCALE'      => $request->input('locale'),
                'APP_TIMEZONE'    => $request->input('timezone'),
                'APP_DATE_FORMAT' => $request->input('date_format'),
                'APP_TIME_FORMAT' => $request->input('time_format'),
            ]);

            return $this->success(trans('core::master.saved'), route('core.config.locale'));
        }

        $this->title = trans('core::config.locale');

        // 语言选项
        $this->languages = Filter::fire('core.config.languages' ,[
            'zh-Hans' => trans('core::config.languages.zh-hans'),
            'zh-Hant' => trans('core::config.languages.zh-hant'),
            'en'      => trans('core::config.languages.en'),
        ]);

        // 日期格式选项
        $this->date_formats = Filter::fire('core.config.date.formats' ,[
            //'Y年m月d日' => Carbon::now()->format('Y年m月d日'),
            'Y-m-d' => Carbon::now()->format('Y-m-d'),
            'Y/m/d' => Carbon::now()->format('Y/m/d'),
            'Y.m.d' => Carbon::now()->format('Y.m.d'),
        ]);

        // 时间选项
        $this->time_formats = Filter::fire('core.config.time.formats' ,[
            //'a g:i' => Carbon::now()->format('a g:i'),
            'H:i:s' => Carbon::now()->format('H:i:s'),
            'H:i'   => Carbon::now()->format('H:i'),
        ]);
        // 时区选项
        $timezones = [];
        foreach(timezone_identifiers_list() as $key => $zone) {
            $continents = explode('/',$zone)[0];
            $timezones[$continents][$zone] = 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone($zone)))->format('P').' - '.$zone;    
        }

        $this->timezones = $timezones;

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
                'APP_DEBUG'        => $request->input('debug', 0) ? 'true' : 'false',
                //'APP_KEY'          => $request->input('key'), //TODO: 未知问题，无法保存
                'APP_ADMIN_PREFIX' => $request->input('admin_prefix', 'admin'),
                'APP_LOG'          => $request->input('log', 'single'),
                'APP_LOG_LEVEL'    => $request->input('log_level', 'debug'),
            ]);

            // 更改后台地址，本地或者测试环境下，route 已经加载，无法重新载入, 改用url生成
            // $this->app['config']->set('app.admin_prefix', $request->input('admin_prefix', 'admin'));
            // $redirectTo = route('core.config.safe');
            $redirectTo = url($request->input('admin_prefix', 'admin').'/core/config/safe');

            return $this->success(trans('core::master.saved'), $redirectTo);
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
