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
            $this->config('core', $request->all());

            return $this->success(trans('core::master.saved'));
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
            $config  = $request->input('image.watermark');
            $source  = resource_path('watermark/test.jpg');
            $preview = 'temp/preview/watermark_test.jpg';
            $image   = app('image')->make($source);
            
            if ($config['type'] == 'image' && $watermark = $config['image']) {
                $watermark = public_path($watermark);
                $watermark = app('image')->make($watermark);
                $watermark->opacity($config['opacity']);
                
                // 插入水印
                $image->insert($watermark, $config['position'], $config['offset']['x'], $config['offset']['y']);     
            }

            if ($config['type'] == 'text' && $text = $config['text']) {
                // 修正文字和图片位置兼容性
                $transform_positions = [
                    'top-left'     => 'top-left',
                    'top'          => 'top-center',
                    'top-right'    => 'top-right',
                    'left'         => 'middle-left',
                    'center'       => 'middle-center',
                    'right'        => 'middle-right',
                    'bottom-left'  => 'bottom-left',
                    'bottom'       => 'bottom-center',
                    'bottom-right' => 'bottom-right',
                ];

                $position = explode('-', $transform_positions[$config['position']]);
                
                foreach ($position as $value) {
                    if (in_array(strtolower($value), array('top', 'bottom', 'middle'))) {
                        $config['font']['valign'] = $value;
                    }
                    if (in_array(strtolower($value), array('left', 'right', 'center'))) {
                        $config['font']['align'] = $value;
                    }
                }

                $x = $config['offset']['x'];
                $y = $config['offset']['y'];

                switch ($config['font']['align']) {
                    case 'center':
                        $x = $image->width() / 2;
                        break;
                    case 'right':
                        //$x = $image->width() - 3 - $config['offset']['x'];
                        $x = $image->width() - $config['offset']['x'];
                        break;
                }
                switch ($config['font']['valign']) {
                    case 'middle':
                        //$y = $image->height() / 2 + $config['offset']['y'];
                        $y = $image->height() / 2;
                        break;
                    case 'bottom':
                        //$y = $image->height() - 4 - $config['offset']['y'];
                        $y = $image->height() - $config['offset']['y'];
                        break;
                }

                $image->text($text, $x, $y, function ($font) use($config) {
                    $font->file(base_path($config['font']['file']));
                    $font->size($config['font']['size']);
                    $font->align($config['font']['align']);  //left, right or center. 
                    $font->valign($config['font']['valign']); //top, bottom or middle.
                    
                    // 如果透明度设置值小于100，转换成对应的rgba #ffffff to rgba array(255, 255, 255, 0.5)
                    if ($config['opacity'] < 100) {
                        $color = str_replace('#', '', $config['font']['color']);
                        $rgba = [
                            hexdec(substr($color, 0, 2)),
                            hexdec(substr($color, 2, 2)),
                            hexdec(substr($color, 4, 2)),
                            $config['opacity']/100
                        ];
                        $config['font']['color'] = $rgba;                      
                    }

                    $font->color($config['font']['color']); 
                     
                });              
            }

            $image->save(public_path($preview), $config['quality']);

            return $this->success(url($preview).'?token='.str_random(20));
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

        return $this->view();
    }         
}
