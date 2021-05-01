<?php

namespace Zotop\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;

class Activator
{
    use Macroable;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Array of modules activation
     *
     * @var array
     */
    protected $modules = [];

    /**
     * 缓存键名
     *
     * @var string
     */
    private $cacheKey;


    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->cacheKey = $this->app['config']->get('modules.cache.key') . '-activator';
        $this->modules = $this->getModules();
    }

    /**
     * 从缓存中获取全部模块或者模块信息
     *
     * @param string|null $key 键名
     * @return mixed
     */
    public function modules($key = null)
    {
        if ($key) {
            return Arr::get($this->modules, $key);
        }

        return $this->modules;
    }

    /**
     * 获取原始的模块数据
     *
     * @return array
     */
    private function getModules()
    {
        try {
            return $this->app['cache']->rememberForever($this->cacheKey, function () {
                return DB::table('modules')->get()->keyBy('module')->transform(function ($item) {
                    $item->config = json_decode($item->config, true);
                    $item->disabled = (bool)$item->disabled;
                    return (array)$item;
                })->toArray();
            });
        } catch (QueryException $e) {
            return [];
        }
    }


    /**
     * 模块是否安装
     *
     * @param Module $module
     * @return boolean
     */
    public function isInstalled(Module $module)
    {
        if ($this->modules($module->getLowerName())) {
            return true;
        }

        return false;
    }

    /**
     * 模块是否禁用
     *
     * @param Module $module
     * @return boolean
     */
    public function isDisabled(Module $module)
    {
        if (!$this->isInstalled($module)) {
            return true;
        }

        if ($this->modules($module->getLowerName() . '.disabled')) {
            return true;
        }

        return false;
    }

    /**
     * 安装的模块版本号
     *
     * @param Module $module
     * @return boolean
     */
    public function getVersion(Module $module)
    {
        return $this->modules($module->getLowerName() . '.version');
    }

    /**
     * 获取模块配置
     *
     * @param Module $module
     * @return array
     */
    public function getConfig(Module $module)
    {
        if ($this->isInstalled($module)) {
            return $this->modules($module->getLowerName() . '.config');
        }

        return [];
    }

    /**
     * 设置模块配置
     *
     * @param Module $module
     * @param array $data
     * @return boolean
     */
    public function setConfig(Module $module, array $data = [])
    {
        // 本地设置
        $config = $module->getOriginalConfig();

        // 当前设置
        $current = $module->getConfig();

        // 深度合并 config, current 和 data
        foreach ([$current, $data] as $merge) {
            foreach (Arr::dot($merge) as $key => $value) {
                Arr::set($config, $key, $value);
            }
        }

        return $this->update($module, [
            'config' => json_encode($config),
        ]);
    }

    /**
     * 安装
     *
     * @param Module $module
     * @return boolean
     */
    public function install(Module $module)
    {
        DB::table('modules')->insert([
            'module'     => $module->getLowerName(),
            'version'    => $module->getOriginalVersion(),
            'config'     => json_encode($module->getOriginalConfig()),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $this->app['cache']->forget($this->cacheKey);
        return true;
    }

    /**
     * 更新信息
     *
     * @param Module $module
     * @param array $data
     * @return boolean
     */
    public function update(Module $module, array $data)
    {
        $data['updated_at'] = now()->format('Y-m-d H:i:s');

        DB::table('modules')->where('module', $module->getLowerName())->update($data);

        $this->app['cache']->forget($this->cacheKey);
        return true;
    }

    /**
     * 启用
     *
     * @param Module $module
     * @return boolean
     */
    public function enable(Module $module)
    {
        return $this->update($module, [
            'disabled' => 0,
        ]);
    }

    /**
     * 禁用
     *
     * @param Module $module
     * @return boolean
     */
    public function disable(Module $module)
    {
        return $this->update($module, [
            'disabled' => 1,
        ]);
    }


    /**
     * 升级
     *
     * @param Module $module
     * @return boolean
     */
    public function upgrade(Module $module)
    {
        // 更新配置
        $this->setConfig($module);

        // 更新版本信息
        return $this->update($module, [
            'version' => $module->getOriginalVersion(),
        ]);
    }

    /**
     * 卸载
     *
     * @param Module $module
     * @return boolean
     */
    public function uninstall(Module $module)
    {
        DB::table('modules')->where('module', $module->getLowerName())->delete();

        $this->app['cache']->forget($this->cacheKey);
        return true;
    }
}
