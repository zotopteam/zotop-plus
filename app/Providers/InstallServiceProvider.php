<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
 
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('installed', function() {
            return true === $this->app['config']->get('app.installed', false);
        });

        // 检查是否已经安装
        $this->checkInstalled();      
    }

    /**
     * 检查是否安装
     * 
     * @return [type] [description]
     */
    public function checkInstalled()
    {
        if ($this->app['installed']) {
            return ;
        }

        if ($this->app['request']->is('install', 'install/*', '_debugbar/*')) {
            // 加载安装路由
            $this->loadRoutesFrom(base_path('/routes/install.php'));
            return ;
        }

        // 从.env.example复制并生成.env
        $dot_env = base_path('.env');

        if (!$this->app['files']->isFile($dot_env)) {
            $this->app['files']->copy(base_path('.env.example'), $dot_env);
        }

        // 强制进入安装
        header('Location:'.url('install'));
        
        exit('The cms has not been installed. Please use the installer to install it');
    }    
}
