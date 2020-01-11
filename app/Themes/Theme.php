<?php
namespace App\Themes;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class Theme
{
    use Macroable,ForwardsCalls;

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
     * __construct
     * @param Application $app [description]
     */
    public function __construct(Application $app, $manifest)
    {
        $this->app        = $app;
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
            $description = trans($description);
        }

        return $description;
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
     * Register the theme event.
     *
     * @param string $event
     */
    protected function dispatch($event)
    {
        $this->app['events']->dispatch(sprintf('themes.%s.' . $event, $this->getLowerName()), [$this]);
    }    


    /**
     * 删除
     * @return void
     */
    public function delete()
    {
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
     * @param  string $name    文件名
     * @param  array  $args    参数
     * @param  mixed $default 默认值
     * @return mixed
     */
    public function data($name, array $args=[], $default=null)
    {
        $file = $this->getPath('data'.DIRECTORY_SEPARATOR.$name.'.php');

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
        $path = $this->app['config']->get('themes.paths.assets');
        $base = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $path);

        $url = $this->app['url']->asset($base.'/'.$this->getLowerName().'/'. $asset);
        $url = str_replace(['http://', 'https://'], '//', $url);

        return $url.'?version='.$this->getVersion();
    }

    /**
     * 注册
     * @return void
     */
    public function active()
    {
        $this->dispatch('activing');

        $this->registerTranslation();
        $this->registerViews();
        $this->registerFiles();

        $this->dispatch('actived');
    }

    /**
     * 注册view
     * 
     * @return void
     */
    public function registerViews()
    {
        //注册全局views路径，实现errors寻址
        $this->app['config']->set('view.paths', Arr::prepend(
            $this->app['config']->get('view.paths'),
            $this->path.'/views')
        );

        // 注册当前模块和主题的views，实现view在主题和模块中寻址
        foreach ($this->app['modules']->enabled() as $module) {
            $this->app['view']->addNamespace($module->getLowerName(), [
                $this->path.'/views/'.$module->getLowerName(),
                $module->getPath() . '/Resources/views/'.$this->type
            ]);      
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
     * 注册模块翻译文件
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
