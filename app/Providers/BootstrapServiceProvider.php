<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {   
        $this->app['modules']->boot();
        $this->themeActive();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['modules']->register();
        $this->registerCurrent();
    }

    /**
     * 注册当前类型[后台、前台、api]，当前语言和当前主题
     * @return null
     */
    public function registerCurrent()
    {
        // 注册当前类型，根据uri的第一个段来判断是前台、后台或者api
        $this->app->singleton('current.type', function($app) {
            
            // 类型组
            $types = $app['hook.filter']->fire('current.types', [
                'api'   => $app['config']->get('app.api_prefix', 'api'),
                'admin' => $app['config']->get('app.admin_prefix', 'admin'),                
            ], $app);

            // 获取url的第一个部分
            $begin = $app['request']->segment(1);            

            // 搜索类型
            $type = array_search(strtolower($begin), array_map('strtolower', $types));

            // 默认为前端
            if (empty($type)) {
                $type  = 'front';
            }
            
            return $app['hook.filter']->fire('current.type', $type, $app);
        });

        // 注册当前语言
        $this->app->singleton('current.locale', function($app) {
            return $app['hook.filter']->fire('current.locale', $app->getLocale(), $app);
        });

        // 注册当前主题，默认为：theme.admin，theme.front，theme.api
        $this->app->singleton('current.theme', function($app) {
            $theme = $app['config']->get('theme.'.$app['current.type'], $app['current.type']);
            return $app['hook.filter']->fire('current.theme', $theme, $app);
        });
    }

    /**
     * 注册主题
     *
     * @return void
     */
    protected function themeActive()
    {
        // 获取主题目录并注册全部主题        
        $path = $this->app['config']->get('themes.paths.themes', base_path('/themes'));

        $dirs = $this->app['files']->directories($path);

        foreach ($dirs as $dir) {
            $this->app['theme']->registerPath($dir);
        }

        // 启用当前主题
        $this->app['theme']->active();
    }        
}
