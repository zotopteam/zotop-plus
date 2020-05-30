<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Modules\Routing\AdminController;
use App\Modules\Traits\ModuleConfig;
use App\Support\ImageFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;

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

            //设置toggle未选择时enabled为0
            $config = $request->all();

            // 写入配置组
            $this->config('core', $config);

            return $this->success(trans('master.saved'));
        }

        $this->title  = trans('core::config.upload');
        $this->config = config('core');

        return $this->view();
    }

    /**
     * 水印测试
     * 
     * @param  Request $request
     * @return json
     */
    public function watermarktest(Request $request)
    {
        // 生成加水印后图片预览
        if ($request->isMethod('POST')) {
            $config = $request->input('image.watermark');
            $target = 'previews/watermarks/test.jpg';
            $source = resource_path('watermark/test.jpg');

            // 生成水印图片
            $source = Image::make($source);
            $source = ImageFilter::apply($source, 'core-watermark', $config);
            $source->save(public_path($target));

            return url($target) . '?token=' . str_random(20);
        }
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
            $this->env([
                'MAIL_DRIVER'       => $request->input('driver'),
                'MAIL_FROM_ADDRESS' => $request->input('from.address'),
                'MAIL_FROM_NAME'    => $request->input('from.name'),
                'MAIL_HOST'         => $request->input('host'),
                'MAIL_PORT'         => $request->input('port'),
                'MAIL_USERNAME'     => $request->input('username'),
                'MAIL_PASSWORD'     => $request->input('password'),
                'MAIL_ENCRYPTION'   => $request->input('encryption'),
            ]);

            return $this->success(trans('master.saved'));
        }

        $this->title = trans('core::config.mail');

        return $this->view();
    }

    /**
     * 发送测试邮件
     * 
     * @param  Request $request
     * @return mixed
     */
    public function mailtest(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            // 邮件接收者
            $to = $request->input('mailtest_sendto');

            // 设置当前参数
            $this->app['config']->set('mail', $request->all());

            // 使用新配置
            (new \Illuminate\Mail\MailServiceProvider($this->app))->register();

            // 发送邮件
            $this->app->make('mailer')->to($to)->send(new \Modules\Core\Emails\TestMail());

            return $this->success(trans('master.operated'));
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
            $this->env([
                'APP_LOCALE'      => $request->input('locale'),
                'APP_TIMEZONE'    => $request->input('timezone'),
                'APP_DATE_FORMAT' => $request->input('date_format'),
                'APP_TIME_FORMAT' => $request->input('time_format'),
            ]);

            return $this->success(trans('master.saved'), route('core.config.locale'));
        }

        $this->title = trans('core::config.locale');

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

            // 写入系统配置组
            $this->config('core', $request->all());

            // 写入ENV配置
            $this->env([
                'APP_DEBUG'        => $request->input('debug') ? 'true' : 'false',
                'APP_ENV'          => $request->input('env', 'production'),
            ]);

            // 更改后台地址，本地或者测试环境下，route 已经加载，无法重新载入, 改用url生成
            $redirectTo = url($request->input('backend.prefix', 'admin') . '/core/config/safe');

            return $this->success(trans('master.saved'), $redirectTo);
        }

        $this->title  = trans('core::config.safe');
        $this->config = config('app') + config('core');

        return $this->view();
    }
}
