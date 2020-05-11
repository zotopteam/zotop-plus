<?php
namespace App\Modules;

use App\Modules\Exceptions\ModuleNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Support\Traits\Macroable;

class Repository
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
     * 实例化的模块数组
     * @var array
     */
    protected $modules = [];

    /**
     * 加载的资源完整url列表
     * @var array
     */
    protected $loads = [];

    /**
     * 初始化
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 扫描模块，获取模块原始数据
     * @return array
     */
    protected function scan()
    {
        $modules = [];

        // 扫描模块目录，获取全部模块数据
        $path      = $this->app['config']->get('modules.paths.modules');
        $manifests = $this->app['files']->glob("{$path}/*/module.json");

        foreach ($manifests as $manifest) {
            if ($attributes = json_decode($this->app['files']->get($manifest), true)) {
                $name  = strtolower($attributes['name']);
                $order = floatval($attributes['order']);
                $modules[$name] = [
                    'path'       => dirname($manifest),
                    'order'      => $order,
                    'attributes' => $attributes,
                ];
            }
        }

        // 按照json中的 order 值 asc 排序
        uasort($modules, function ($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return $a['order'] > $b['order'] ? 1 : -1;
        });

        return $modules;        
    }

    /**
     * 获取实例化的全部模块
     * @return array
     */
    public function all()
    {
        // 第一次获取全部模块时，填充全局数组
        if (empty($this->modules)) {
            foreach ($this->scan() as $name => $module) {
                $this->modules[$name] = new Module($this->app, $module['path'], $module['attributes']);
            }
        }

        return $this->modules;
    }

    /**
     * 获取启用或者禁用的模块
     * @param  boolean $enabled true=获取启用 false=获取禁用
     * @return array
     */
    public function enabled($enabled=true)
    {
        $modules = $this->all();

        foreach ($modules as $key => $module) {

            if ($enabled && $module->isDisabled()) {
                unset($modules[$key]);
            }

            if (! $enabled && $module->isEnabled()) {
                unset($modules[$key]);
            }            

        }

        return $modules;
    }

    /**
     * 获取安装或者未安装的模块
     * @param  boolean $enabled true=获取启用 false=获取禁用
     * @return array
     */
    public function installed($installed=true)
    {
        $modules = $this->all();

        foreach ($modules as $key => $module) {

            if ($installed && !$module->isInstalled()) {
                unset($modules[$key]);
            }

            if (! $installed && $module->isInstalled()) {
                unset($modules[$key]);
            }            

        }

        return $modules;
    }    

    /**
     * 检查模块是否存在
     * @param  string  $name 模块名称
     * @return boolean
     */
    public function has(string $name)
    {
        return array_key_exists(strtolower($name), $this->all());
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
        if ($module = $this->find($name)) {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
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

        if ($module = $this->findOrFail($module)) {
            $data = $module->data($file, $args, $default);
        }

        // 以name为钩子扩展$data
        return $this->app['hook.filter']->fire($name, $data, $args, $default);
    }

    /**
     * 获取资源url
     * @param  string $asset 模块名称:文件相对路径，例如：core:css/global.css
     * @param boolean $version 是否附带版本号
     * @return string
     */
    public function asset($asset, $version=true)
    {
        list($module, $url) = explode(':', $asset);

        if ($module = $this->findOrFail($module)) {
            return $module->asset($url, $version);
        }

        return null;
    }

    /**
     * 加载资源文件
     * @param  mixed $asset 资源路径 core:aaa/bbb.js || core:bbb/aaa.css
     * @param  string $type  类型，js || css，不指定类型时，根据资源的后缀自动判断
     * @return string
     */
    public function load($asset, $type=null)
    {
        $load = [];

        foreach (Arr::wrap($asset) as $asset) {

            // 确定文件类型
            $type = $type ? strtolower($type) : strtolower(Str::afterLast($asset, '.'));
            
            // 获取资源的完整url
            $url  = $this->asset($asset, true);

            // 如果已经加载，不再次加载和输出
            if ($url && !isset($this->loads[$url])) {
                $this->loads[$url] = true;

                if ($type == 'js') {
                    $load[] = '<script src="'.$url.'"></script>';
                }

                if ($type == 'css') {
                    $load[] = '<link href="'.$url.'" rel="stylesheet">';
                }            
            }
        }

        return implode($load, "\r\n");
    }

    /**
     * 注册
     * @return void
     */
    public function register()
    {
        foreach ($this->enabled() as $module) {
            $module->register();
        }
    }

    /**
     * 启动
     * @return void
     */
    public function boot()
    {
        foreach ($this->enabled() as $module) {
            $module->boot();
        }        
    }
}
