<?php

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\PublishConfig;
use Nwidart\Modules\Module;
use Modules\Core\Entities\Config;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * 中间件
     *
     * @var array
     */
    protected $middlewares = [
        'admin' => 'AdminMiddleware',
        'allow' => 'AllowMiddleware',
    ];    

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        // 注册中间件
        $this->registerMiddleware();

        // 注册模块文件
        foreach ($this->app['modules']->getOrdered() as $module) {
            $this->registerConfig($module); 
            $this->registerLanguageNamespace($module);
        }      
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * 注册中间件
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware()
    {
        foreach ($this->middlewares as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, "Modules\\Core\\Http\\Middleware\\{$middleware}");
        }
    }

    /**
     * 注册模块配置文件
     * 
     * @param Module $module
     * @return void
     */
    protected function registerConfig(Module $module)
    {
        $moduleName = $module->getLowerName();

        // 注入自定义配置
        if ($moduleConfig = Config::get($moduleName)) {
            $this->app['config']->set($moduleConfig);
        }
        

        // $configPath = $module->getPath().'/Config';

        // $configFile = $module->getPath().'/config.php';

        // $configs = include($configFile);

        // foreach ($configs as $key => $value) {

        //     // 插入超级管理员
        //     Config::updateOrCreate([
        //         'key'       => $key,
        //         'module'    => $moduleName,
        //     ],[
        //         'value'     => $value
        //     ]);
        // }        

        // // 注册模块根目录下的配置
        // if ($this->app['files']->isFile($configFile)) {
        //     $this->mergeConfigFrom($configFile, "module.$moduleName");
        //     $this->publishes([
        //        $configFile => config_path("module/$moduleName.php"),
        //     ], 'config');
        // }

        // TODO：部分config 存入数据库，从数据库中读取当前module的配置并缓存后覆盖默认数据，去除存储在config中的文件
        // $config = $this->app['config']->get($moduleName, []);
        // $config = array_merge($config,['name'=>'zotop']);
        // $this->app['config']->set('site',$config);     

        // 注册模块Config目录下的配置
        // if ($this->app['files']->isDirectory($configPath)) {
        //     foreach ($this->app['files']->files($configPath) as $configFile) {
        //         $fileName = basename($configFile,'.php');
        //         $this->mergeConfigFrom($configFile, "module.$moduleName.$fileName");
        //     }
        // }
    }

    /**
     * 注册模块语言包命名空间
     * 
     * @param Module $module
     * @return void
     */
    protected function registerLanguageNamespace(Module $module)
    {
        $moduleName = $module->getLowerName();        
        $moduleLang = base_path("resources/lang/{$moduleName}");

        // 如果已经publish并且模块语音文件夹存在
        if (is_dir($moduleLang)) {
            return $this->loadTranslationsFrom($moduleLang, $moduleName);
        }

        return $this->loadTranslationsFrom($module->getPath() . '/Resources/lang', $moduleName);
    }

    /**
     * 注册命令行
     * 
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Modules\Core\Console\CreateCommand::class,
            \Modules\Core\Console\CreateThemeCommand::class,
            \Modules\Core\Console\MakeHelpersCommand::class,
            \Modules\Core\Console\MakeMacrosCommand::class,
            \Modules\Core\Console\MakeTraitCommand::class,
            \Modules\Core\Console\AdminControllerCommand::class,
            \Modules\Core\Console\FrontControllerCommand::class,
            \Modules\Core\Console\RebootCommand::class,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
