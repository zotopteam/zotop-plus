<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册当前类型，根据uri的第一个段来判断是前台、后台或者api
        $this->app->singleton('current.type', function ($app) {

            // 获取url的第一个部分
            $begin = strtolower($app['request']->segment(1));

            $type  = 'frontend';

            foreach ($app['config']->get('modules.types') as $key => $value) {
                if ($value['prefix'] && $value['prefix'] == $begin) {
                    $type = $key;
                    break;
                }
            }

            return $app['hook.filter']->fire('current.type', $type, $app);
        });

        // 注册当前语言
        $this->app->singleton('current.locale', function ($app) {
            return $app['hook.filter']->fire('current.locale', $app->getLocale(), $app);
        });

        //注册模块
        $this->app['modules']->register();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 启动模块
        $this->app['modules']->boot();
    }
}
