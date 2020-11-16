<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InstallServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('installed', function () {
            return true === $this->app['config']->get('app.installed', false);
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // 如果已经安装，跳过后续流程
        if ($this->app['installed'] === true) {
            return;
        }

        // 进入安装流程
        $this->install();
    }

    /**
     * 检查是否安装
     *
     * @return void
     */
    public function install()
    {
        // 进入安装页面后，加载安装程序路由
        if ($this->app['request']->is('install', 'install/*', '_debugbar/*')) {
            $this->loadRoutesFrom(base_path('/routes/install.php'));
            return;
        }

        // 未安装且env文件不存在，从.env.example复制并生成.env
        if (!$this->app['files']->exists($dot_env = base_path('.env'))) {
            $this->app['files']->copy(base_path('.env.example'), $dot_env);
        }

        // 强制进入安装
        header('Location:' . url('install'));
        exit;
    }
}
