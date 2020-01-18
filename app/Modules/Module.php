<?php
namespace App\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Artisan;

class Module
{
    use Macroable;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The module manifest
     * @var string
     */
    public $manifest;

    /**
     * The module path
     * @var string
     */
    public $path;

    /**
     * The module attributes
     * @var array
     */
    public $attributes = [];

    /**
     * The module activator
     * @var array
     */
    public $activator;

    /**
     * __construct
     * @param Application $app [description]
     */
    public function __construct(Application $app, $manifest)
    {
        $this->app        = $app;
        $this->activator  = $app['modules.activator'];
        $this->manifest   = $manifest;
        $this->path       = dirname($manifest);        
        $this->attributes = json_decode($app['files']->get($manifest), true);
    }

    /**
     * 获取module.json数据
     * @param  string $key     键名
     * @param  mixed $default 默认值
     * @return mixed
     */
    public function attribute($key, $default=null)
    {
        return Arr::get($this->attributes, strtolower($key), $default);
    }

    /**
     * Get name in lower case.
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }
    /**
     * Get name in studly case.
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->name);
    }
    /**
     * Get name in snake case.
     *
     * @return string
     */
    public function getSnakeName()
    {
        return Str::snake($this->name);
    }

    /**
     * 获取翻译过的标题
     * @param  string $translate   是否翻译
     * @return string
     */
    public function getTitle($translate=true)
    {
        $title = $this->title;

        if ($translate) {

            // 禁用的模块不会自动加载翻译文件，此次加载
            if ($this->isDisabled()) {
                $this->registerTranslation();
            }

            $title = trans($title);
        }

        return $title;
    }

    /**
     * 获取描述
     * @param  string $translate  是否翻译
     * @return string
     */
    public function getDescription($translate=true)
    {
        $description = $this->description;

        if ($translate) {

            // 禁用的模块不会自动加载翻译文件，此次加载
            if ($this->isDisabled()) {
                $this->registerTranslation();
            }

            $description = trans($description);
        }

        return $description;
    }

    /**
     * 获取版本
     * @param  boolean $original true=原始的版本号 false=安装的版本号
     * @return string
     */
    public function getVersion($original=false)
    {
        if ($original) {
            return $this->version;
        }

        return $this->activator->getVersion($this);
    }

    /**
     * 获取版本
     * @param  boolean $original true=原始的版本号 false=安装的版本号
     * @return array
     */
    public function getConfig($original=false)
    {
        if ($original) {

            $path = $this->getPath('config.php');

            if ($this->app['files']->exists($path)) {

                $config = require $path;

                if (is_array($config)) {
                    return $config;
                }
            }

            return [];
        }

        return $this->activator->getConfig($this);        
    }

    /**
     * 设置配置
     * @param array $config [description]
     */
    public function setConfig(array $config=[])
    {
        return $this->activator->setConfig($this, $config);
    }


    /**
     * 获取路径
     *
     * @return string
     */
    public function getPath($file=null)
    {
        if ($file) {
            return $this->path.DIRECTORY_SEPARATOR.ltrim($file, DIRECTORY_SEPARATOR);
        }

        return $this->path;
    }

    /**
     * 模块是否安装
     * @return boolean
     */
    public function isInstalled()
    {
        return $this->activator->isInstalled($this);
    }

    /**
     * 模块是否启用
     * @return boolean
     */
    public function isEnabled()
    {
        return ! $this->activator->isDisabled($this);
    }

    /**
     * 模块是否禁用
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->activator->isDisabled($this);
    }

    /**
     * Register the module event.
     *
     * @param string $event
     */
    protected function dispatch($event)
    {
        $this->app['events']->dispatch(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }    

    /**
     * 启用
     * @return void
     */
    public function enable()
    {   
        $this->dispatch('enabling');

        $this->activator->enable($this);

        $this->dispatch('enabled');
    }

    /**
     * 禁用
     * @return void
     */
    public function disable()
    {
        $this->dispatch('disabling');

        $this->activator->disable($this);

        $this->dispatch('disabled');
    }

    /**
     * 安装
     * @return void
     */
    public function install()
    {
        // 安装之前注册模块
        $this->register();

        $this->dispatch('installing');

        // 迁移数据库
        Artisan::call('module:migrate', [
            'module'  => $this->name,
            '--force' => true,
            '--seed'  => (boolean) $this->seed,
        ]);

        // 发布资源
        Artisan::call('module:publish', [
            'module'   => $this->name,
            '--action' => 'publish',
        ]);

        $this->activator->install($this);
        $this->dispatch('installed');
    }

    /**
     * 升级
     * @return [type] [description]
     */
    public function upgrade()
    {
        $this->dispatch('upgrading');

        // 迁移数据库
        Artisan::call('module:migrate', [
            'module'  => $this->name,
            '--force' => true,
            '--seed'  => (boolean) $this->seed,
        ]);

        // 发布资源
        Artisan::call('module:publish', [
            'module'   => $this->name,
            '--action' => 'publish',
        ]);

        $this->activator->upgrade($this);
        $this->dispatch('upgraded');        
    }

    /**
     * 卸载
     * @return void
     */
    public function uninstall()
    {
        $this->dispatch('uninstalling');

        // 卸载数据库
        Artisan::call('module:migrate-reset', [
            'module'  => $this->name,
            '--force' => true,
        ]);        
        // 删除资源
        Artisan::call('module:publish', [
            'module'   => $this->name,
            '--action' => 'unpublish',
        ]);

        $this->activator->uninstall($this);
        $this->dispatch('uninstalled');
    }

    /**
     * 删除
     * @return void
     */
    public function delete()
    {
        $this->dispatch('deleting');
        $this->app['files']->deleteDirectory($this->getPath());    
        $this->dispatch('deleted');
    }


    /**
     * 获取模块Data目录下的数据
     * @param  string $name    文件名
     * @param  array  $args    参数
     * @param  mixed $default 默认值
     * @return mixed
     */
    public function data($name, array $args=[], $default=null)
    {
        $file = $this->getPath('Data'.DIRECTORY_SEPARATOR.$name.'.php');

        if (! $this->app['files']->isFile($file)) {
            return $default;
        }

        return value(function() use ($file, $args) {
            @extract($args);
            return require $file;
        });        
    }

    /**
     * 获取资源url
     * @param  string $asset 资源路径
     * @return string
     */
    public function asset($asset)
    {
        $path = $this->app['config']->get('modules.paths.assets');
        $base = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $path);

        $url = $this->app['url']->asset($base.'/'.$this->getLowerName().'/'. $asset);
        $url = str_replace(['http://', 'https://'], '//', $url);

        return $url.'?version='.$this->getVersion();
    }

    /**
     * 注册
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerAlias();
        $this->registerProviders();
        $this->registerFiles();

        $this->dispatch('register');
    }

    /**
     * 注册别名
     * @return void
     */
    public function registerAlias()
    {
        $loader = AliasLoader::getInstance();
        foreach ($this->attribute('aliases', []) as $alias => $class) {
            $loader->alias($alias, $class);
        }               
    }

    /**
     * 注册服务提供者
     * @return void
     */
    public function registerProviders()
    {
        // $cachePath = Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->getCachedServicesPath());

        // (new ProviderRepository($this->app, new Filesystem(), $cachePath))
        //     ->load($this->attribute('providers', []));   

        foreach ($this->attribute('providers', []) as $provider) {
            $this->app->register($provider);
        }        
    }

    /**
     * 注册全局文件
     * @return void
     */
    public function registerFiles()
    {
        foreach ($this->attribute('files', []) as $file) {
            include $this->path . '/' . $file;
        }              
    }

    /**
     * 启动
     * @return void
     */
    public function boot()
    {
        $this->registerTranslation();
        $this->registerFactories();
        
        $this->dispatch('boot');
    }

    /**
     * 注册模块翻译文件
     * @return void
     */
    protected function registerTranslation()
    {
        if (is_dir($path = $this->path . DIRECTORY_SEPARATOR . $this->app['config']->get('modules.paths.dirs.lang'))) {
            $this->app['translator']->addJsonPath($path);
            $this->app['translator']->addNamespace($this->getLowerName(), $path);
        }
    }

    /**
     * 注册模块配置
     * @return void
     */
    protected function registerConfig()
    {
        //加载模块配置
        $this->app['config']->set($this->getLowerName(), $this->getConfig());
    }    

    /**
     * 注册Factories
     * @return void
     */
    protected function registerFactories()
    {
        // 非产品环境下注册Factories
        if (! $this->app->environment('production')) {
            $this->app->make(Factory::class)->load($this->path . DIRECTORY_SEPARATOR . $this->app['config']->get('modules.paths.dirs.factory'));
        }        
    }    

    /**
     * Handle call to __get method.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return Arr::get($this->attributes, strtolower($key));
    }

    /**
     * Handle call to __set method.
     *
     * @param $key
     * @return mixed
     */
    public function __set($key, $value)
    {
        return Arr::set($this->attributes, strtolower($key), $value);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // getName, getTitle……
        if (Str::start(strtolower($method), 'get')) {
            return $this->attribute(Str::after($method, 'get'));
        }

        static::throwBadMethodCallException($method);
    }

    /**
     * Handle call __toString.
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }    
}
