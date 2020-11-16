<?php

namespace App\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;


/**
 * @property string name
 * @property string title
 * @property string description
 * @property string version
 * @property mixed seed
 */
class Module
{
    use Macroable, ForwardsCalls {
        Macroable::__call as macroCall;
    }

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The module path
     *
     * @var string
     */
    public $path;

    /**
     * The module attributes
     *
     * @var array
     */
    public $attributes = [];

    /**
     * The module activator
     *
     * @var \App\Modules\Activator
     */
    public $activator;

    /**
     * __construct
     *
     * @param Application $app [description]
     */
    /**
     * 初始化
     *
     * @param Application $app
     * @param string $path 模块路径
     * @param array $attributes 模块属性
     */
    public function __construct(Application $app, string $path, array $attributes)
    {
        $this->app = $app;
        $this->activator = $app['modules.activator'];
        $this->path = $path;
        $this->attributes = $attributes;
    }

    /**
     * 获取模块属性
     *
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function attribute(string $key, $default = null)
    {
        return Arr::get($this->attributes, strtolower($key), $default);
    }

    /**
     * 获取模块小写名称
     *
     * @return string
     */
    public function getLowerName()
    {
        return strtolower($this->name);
    }

    /**
     * 获取模块驼峰名称
     *
     * @return string
     */
    public function getStudlyName()
    {
        return Str::studly($this->name);
    }

    /**
     * 获取模块蛇式名称
     *
     * @return string
     */
    public function getSnakeName()
    {
        return Str::snake($this->name);
    }

    /**
     * 获取翻译过的标题
     *
     * @return string
     */
    public function getTitle()
    {
        // 禁用的模块不会自动加载翻译文件，此次加载，TODO：加载json翻译有问题，无法临时加载
        if ($this->isDisabled()) {
            $this->registerTranslation();
        }

        return trans($this->title);
    }

    /**
     * 获取描述
     *
     * @return string
     */
    public function getDescription()
    {
        // 禁用的模块不会自动加载翻译文件，此次加载，TODO：加载json翻译有问题，无法临时加载
        if ($this->isDisabled()) {
            $this->registerTranslation();
        }

        return trans($this->description);
    }

    /**
     * 获取module.json中的原始版本号
     *
     * @return string
     */
    public function getOriginalVersion()
    {
        return $this->version;
    }

    /**
     * 获取安装的版本
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->activator->getVersion($this);
    }

    /**
     * 获取模块目录下的config.php值
     *
     * @return array
     */
    public function getOriginalConfig()
    {
        $path = $this->getPath('config.php');

        if ($this->app['files']->exists($path)) {

            $config = require $path;

            if (is_array($config)) {
                return $config;
            }
        }

        return [];
    }

    /**
     * 获取模块设置
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->activator->getConfig($this);
    }

    /**
     * 设置配置
     *
     * @param array $config 配置数组
     * @return bool
     */
    public function setConfig(array $config = [])
    {
        return $this->activator->setConfig($this, $config);
    }

    /**
     * 获取模块路径
     *
     * @param string|null $subpath 子路径或者路径键名
     * @param boolean $isDirKey 是否为子路径键名
     * @return string
     */
    public function getPath($subpath = null, $isDirKey = false)
    {
        if ($subpath) {
            if ($isDirKey) {
                $subpath = $this->app['config']->get("modules.paths.dirs.{$subpath}");
            }
            return $this->path . DIRECTORY_SEPARATOR . ltrim($subpath, DIRECTORY_SEPARATOR);
        }

        return $this->path;
    }

    /**
     * 判断模块是否为某个模块
     *
     * @param mixed $module
     * @return boolean
     */
    public function is($module)
    {
        return $this->getLowerName() == strtolower($module);
    }

    /**
     * 模块是否安装
     *
     * @return boolean
     */
    public function isInstalled()
    {
        return $this->activator->isInstalled($this);
    }

    /**
     * 模块是否启用
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !$this->activator->isDisabled($this);
    }

    /**
     * 模块是否禁用
     *
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
    protected function dispatch(string $event)
    {
        $this->app['events']->dispatch(sprintf('modules.%s.' . $event, $this->getLowerName()), [$this]);
    }

    /**
     * 启用
     *
     * @return void
     */
    public function enable()
    {
        // 启用之前注册模块
        $this->register();
        // 启用之前启用模块
        $this->boot();

        $this->dispatch('enabling');
        $this->activator->enable($this);
        $this->dispatch('enabled');
    }

    /**
     * 禁用
     *
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
     *
     * @return void
     */
    public function install()
    {
        // 安装之前注册模块
        $this->register();
        // 安装之前启用模块
        $this->boot();

        $this->dispatch('installing');

        // 迁移数据库
        Artisan::call('module:migrate', [
            'module'  => $this->name,
            '--force' => true,
            '--seed'  => (bool)$this->seed,
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
     */
    public function upgrade()
    {
        $this->dispatch('upgrading');

        // 迁移数据库
        Artisan::call('module:migrate', [
            'module'  => $this->name,
            '--force' => true,
            '--seed'  => (bool)$this->seed,
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
     *
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
     *
     * @return void
     */
    public function delete()
    {
        // 删除之前注册模块
        $this->register();
        // 删除之前启用模块
        $this->boot();

        $this->dispatch('deleting');
        $this->app['files']->deleteDirectory($this->getPath());
        $this->dispatch('deleted');
    }


    /**
     * 获取模块Data目录下的数据
     *
     * @param string $name 文件名
     * @param array $args 参数
     * @param mixed $default 默认值
     * @return mixed
     */
    public function data(string $name, array $args = [], $default = null)
    {
        $file = $this->getPath('Data' . DIRECTORY_SEPARATOR . $name . '.php');

        if (!$this->app['files']->isFile($file)) {
            return $default;
        }

        return value(function () use ($file, $args) {
            @extract($args);
            return require $file;
        });
    }

    /**
     * 获取资源url
     *
     * @param string $asset 资源路径
     * @param boolean $version 是否附带版本号
     * @return string|void
     */
    public function asset(string $asset, $version = true)
    {
        // 获取资源的完整路径 (publish之后在public目录下的路径)
        $path = $this->app['config']->get('modules.paths.assets') . DIRECTORY_SEPARATOR . $this->getLowerName() . DIRECTORY_SEPARATOR . $asset;

        // 如果文件不存在，则直接返回null
        if (!file_exists($path)) {
            return null;
        }

        // 取出基本路径并转换为url
        $uri = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $path);
        $url = str_replace(['http://', 'https://'], '//', $this->app['url']->asset($uri));

        // 为防止缓存，追加版本号
        if ($version) {
            return $url . '?version=' . $this->getVersion();
        }

        return $url;
    }

    /**
     * 注册
     *
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
     *
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
     *
     * @return void
     */
    public function registerProviders()
    {
        foreach ($this->attribute('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * 注册全局文件
     *
     * @return void
     */
    public function registerFiles()
    {
        foreach ($this->attribute('files', []) as $file) {
            include $this->getPath($file);
        }
    }

    /**
     * 启动
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslation();

        $this->dispatch('boot');
    }

    /**
     * 注册模块翻译文件
     *
     * @return void
     */
    protected function registerTranslation()
    {
        if (is_dir($path = $this->getPath('lang', true))) {
            $this->app['translator']->addJsonPath($path);
            $this->app['translator']->addNamespace($this->getLowerName(), $path);
        }
    }

    /**
     * 注册模块配置
     *
     * @return void
     */
    protected function registerConfig()
    {
        //加载模块配置
        $this->app['config']->set($this->getLowerName(), $this->getConfig());
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
     * @param $value
     * @return mixed
     */
    public function __set($key, $value)
    {
        return Arr::set($this->attributes, strtolower($key), $value);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param $method
     * @param $parameters
     * @return mixed|void
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        // getName, getTitle……
        if (Str::start(strtolower($method), 'get')) {
            return $this->attribute(Str::after($method, 'get'));
        }

        static::throwBadMethodCallException($method);
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStudlyName();
    }
}
