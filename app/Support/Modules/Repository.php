<?php
namespace App\Support\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class Repository
{
    use Macroable;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * __construct
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 扫描模块
     * @return array
     */
    public function scan()
    {
        static $modules = [];

        if (empty($modules)) {

            $path = $this->app['config']->get('modules.paths.modules');
            $path = Str::endsWith($path, '/*') ? $path : Str::finish($path, '/*');

            $manifests = $this->app['files']->glob("{$path}/module.json");

            foreach ($manifests as $manifest) {
                $module = new \App\Support\Modules\Module($this->app, $manifest);
                if ($key = $module->getLowerName()) {
                    $modules[$key] = $module;
                }
            }

        }

        return $modules;
    }

    /**
     * 获取排序后的全部模块
     * @return array
     */
    public function all()
    {
        $modules = $this->scan();

        // 按照json中的 order 值 asc 排序
        uasort($modules, function ($a, $b) {
            if ($a->order == $b->order) {
                return 0;
            }
            return $a->order > $b->order ? 1 : -1;
        });

        return $modules;
    }

    public function getOrdered()
    {
        return $this->all();
    }

    /**
     * 按照名称获取模块
     * @param  string $name 模块名称
     * @return module
     */
    public function find(string $name)
    {
        return Arr::get($this->all(), strtolower($name));
    }

    /**
     * Find a specific module, if there return that, otherwise throw exception.
     *
     * @param $name
     * @return Module
     * @throws NotFoundException
     */
    public function findOrFail($name)
    {
        $module = $this->find($name);

        if ($module !== null) {
            return $module;
        }
        throw new \App\Support\Modules\NotFoundException("Module [{$name}] does not exist!");
    }

    /**
     * 获取模块Data目录下的数据
     * @param  string $name    模块名称::文件名称 例如：core::mimetype
     * @param  array  $args    参数
     * @param  mixed $default 默认值
     * @return mixed
     */
    public function data($name, array $args=[], $default=null)
    {
        $data = $default;

        list($module, $file) = explode('::', $name);

        $module = $this->findOrFail($module);

        if ($module) {
            $data = $module->data($file, $args, $default);
        }

        return $data;
    }

    /**
     * 获取资源url
     * @param  string $asset 模块名称:文件相对路径，例如：core:css/global.css
     * @return string
     */
    public function asset($asset)
    {
        list($module, $url) = explode(':', $asset);

        $module = $this->findOrFail($module);

        if ($module) {
            $asset = $module->asset($url);
        }

        return null;
    }

    /**
     * 注册
     * @return void
     */
    public function register()
    {
        foreach ($this->all() as $module) {

            if ($module->isDisabled()) {
                continue;
            }            

            $module->register();
        }
    }

    /**
     * 启动
     * @return void
     */
    public function boot()
    {
        foreach ($this->all() as $module) {
            
            if ($module->isDisabled()) {
                continue;
            }

            $module->boot();
        }        
    }
}
