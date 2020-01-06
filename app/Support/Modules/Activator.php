<?php
namespace App\Support\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Support\Modules\Module;

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
     * @var string
     */
    private $cacheKey;


    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->cacheKey = $this->app['config']->get('modules.cache.key').'-activator';
        $this->modules = $this->modules();
    }

    /**
     * 获取模块信息
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function modules($key=null)
    {
        static $modules = [];

        if (empty($modules)) {

            $modules = $this->app['cache']->rememberForever($this->cacheKey, function() {
                return $this->getModules();
            });
        }

        if ($key) {
            return Arr::get($modules, $key);
        }

        return $modules;
    }

    /**
     * 获取模块
     * @return array
     */
    private function getModules()
    {
        $modules = [];

        if (Schema::hasTable('modules')) {
            $modules = DB::table('modules')->get()->keyBy('module')->transform(function($item){
                $item->config = json_decode($item->config, true);
                $item->disabled = (bool) $item->disabled;
                return (array) $item;
            })->toArray();
        }

        return $modules;
    }


    /**
     * 模块是否安装
     * @param  Module  $module
     * @return boolean
     */
    public function isInstalled(Module $module)
    {
        if ( $this->modules($module->getLowerName()) ) {
            return true;
        }

        return false;
    }

    /**
     * 模块是否禁用
     * @param  Module  $module
     * @return boolean
     */
    public function isDisabled(Module $module)
    {
        if (! $this->isInstalled($module)) {
            return true;
        }

        if ( $this->modules($module->getLowerName().'.disabled') ) {
            return true;
        }

        return false;
    }

    /**
     * 安装的版本
     * @param  Module  $module
     * @return boolean
     */
    public function getVersion(Module $module)
    {
        return $this->modules($module->getLowerName().'.version');
    }

    /**
     * 模块配置
     * @param  Module  $module
     * @return array
     */
    public function getConfig(Module $module)
    {
        if ($this->isInstalled($module)) {
            return $this->modules($module->getLowerName().'.config');
        }

        return [];
    }

    /**
     * 升级
     * @param  Module  $module
     * @return boolean
     */
    public function setConfig(Module $module, array $config)
    {
        // 本地设置
        $original = $module->getConfig(true);
        //当前设置
        $current  = $module->getConfig();

        // 只允许更新本地设置中有的信息
        if ($config = Arr::only($config, array_keys($original))) {

            // 深层次合并数组
            $config = Arr::dot($config);

            foreach ($config as $key => $value) {
                Arr::set($current, $key, $value);
            }
            
            return $this->update($module, [
                'config'     => json_encode($current),          
            ]);            
        }

        return;
    }    

    /**
     * 安装
     * @param  Module  $module
     * @return boolean
     */
    public function install(Module $module)
    {
        DB::table('modules')->insert([
            'module'     => $module->getLowerName(),
            'version'    => $module->getVersion(true),
            'config'     => json_encode($module->getConfig(true)),
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $this->app['cache']->forget($this->cacheKey);
        return true;
    }

    /**
     * 更新信息
     * @param  Module  $module
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
     * @param  Module  $module
     * @return boolean
     */
    public function enable(Module $module)
    {
        return $this->update($module, [
            'disabled'    => 0,
        ]);
    }

    /**
     * 升级
     * @param  Module  $module
     * @return boolean
     */
    public function disable(Module $module)
    {
        return $this->update($module, [
            'disabled'    => 1,
        ]);
    }    


    /**
     * 升级
     * @param  Module  $module
     * @return boolean
     */
    public function upgrade(Module $module)
    {
        return $this->update($module, [
            'version'    => $module->getVersion(true),
            'config'     => json_encode($module->getConfig(true)),            
        ]);
    }

    /**
     * 卸载
     * @param  Module  $module
     * @return boolean
     */
    public function uninstall(Module $module)
    {
        DB::table('modules')->where('module', $module->getLowerName())->delete();

        $this->app['cache']->forget($this->cacheKey);
        return true;        
    }         
}
