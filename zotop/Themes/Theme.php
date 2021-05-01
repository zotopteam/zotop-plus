<?php

namespace Zotop\Themes;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class Theme
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
     * The module manifest
     *
     * @var string
     */
    public $manifest;

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
     * __construct
     *
     * @param Application $app [description]
     * @param $manifest
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(Application $app, $manifest)
    {
        $this->app = $app;
        $this->manifest = $manifest;
        $this->path = dirname($manifest);
        $this->attributes = json_decode($app['files']->get($manifest), true);
    }

    /**
     * 获取module.json数据
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
     *
     * @return string
     */
    public function getTitle()
    {
        return trans($this->title);
    }

    /**
     * 获取描述
     *
     * @return string
     */
    public function getDescription()
    {
        return trans($this->description);
    }

    /**
     * 获取当前类型
     *
     * @return string
     */
    public function getType()
    {
        return strtolower($this->type);
    }

    /**
     * 获取路径
     *
     * @param string|null $subpath
     * @param bool $isDirKey
     * @return string
     */
    public function getPath($subpath = null, $isDirKey = false)
    {
        if ($subpath) {

            if ($isDirKey) {
                $subpath = $this->app['config']->get("themes.paths.dirs.{$subpath}");
            }

            return $this->path . DIRECTORY_SEPARATOR . ltrim($subpath, DIRECTORY_SEPARATOR);
        }

        return $this->path;
    }

    /**
     * Register the theme event.
     *
     * @param string $event
     */
    protected function dispatch(string $event)
    {
        $this->app['events']->dispatch(sprintf('themes.%s.' . $event, $this->getLowerName()), [$this]);
    }


    /**
     * 删除
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function delete()
    {
        // 删除之前需要启动主题，才能实现该主题事件监听
        $this->active();

        $this->dispatch('deleting');

        // 撤销主题发布文件
        Artisan::call('theme:publish', [
            'theme'    => $this->name,
            '--action' => 'unpublish',
        ]);

        // 删除主题文件
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
        $file = $this->getPath('data' . DIRECTORY_SEPARATOR . $name . '.php');

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
     * @return string
     */
    public function asset(string $asset)
    {
        // 获取资源的完整路径 (publish之后在public目录下的路径)
        $path = $this->app['config']->get('themes.paths.assets') . DIRECTORY_SEPARATOR . $this->getLowerName() . DIRECTORY_SEPARATOR . $asset;

        // 如果文件不存在，则直接返回null
        if (!file_exists($path)) {
            return null;
        }

        // 取出基本路径并转换为url
        $uri = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $path);
        $url = str_replace(['http://', 'https://'], '//', $this->app['url']->asset($uri));

        return $url . '?version=' . $this->getVersion();
    }


    /**
     * 注册
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function active()
    {
        $this->dispatch('activing');

        $this->registerProviders();
        $this->registerFiles();
        $this->registerTranslation();
        $this->registerViews();

        $this->dispatch('actived');
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
     * 注册view
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function registerViews()
    {
        // 主题views路径
        $themeViewPath = $this->getPath('views', true);

        //注册全局views路径，实现errors寻址
        $this->app['config']->set('view.paths', Arr::prepend(
            $this->app['config']->get('view.paths'),
            $themeViewPath
        ));

        // 不带namespace时直接在当前主题中直接寻找
        $this->app['view']->addLocation($themeViewPath);

        // 注册当前模块和主题的views，实现view在主题和模块中寻址
        // 例如主题：default，主题类型：frontend，视图：core::mine.edit
        // 首先寻找主题中的视图： themes/Default/views/core/mine/edit.blade.php
        // 找不到后寻找模块视图： modules/Core/Resources/views/front/mine/edit.blade.php
        foreach ($this->app['modules']->enabled() as $module) {
            $moduleViewPath = $module->getPath('views', true);
            $moduleTypePath = $this->app['config']->get('modules.types.' . $this->getType() . '.dirs.view');
            $this->app['view']->addNamespace($module->getLowerName(), [
                $themeViewPath . DIRECTORY_SEPARATOR . $module->getLowerName(),
                $moduleViewPath . DIRECTORY_SEPARATOR . $moduleTypePath,
            ]);
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
            include $this->path . '/' . $file;
        }
    }


    /**
     * 注册模块翻译文件
     *
     * @return void
     */
    protected function registerTranslation()
    {
        if (is_dir($path = $this->path . '/lang')) {
            $this->app['translator']->addJsonPath($path);
            $this->app['translator']->addNamespace('theme', $path);
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
     * @param string $method
     * @param array $parameters
     * @return mixed|void
     */
    public function __call(string $method, array $parameters)
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
