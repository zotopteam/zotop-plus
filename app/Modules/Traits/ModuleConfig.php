<?php

namespace App\Modules\Traits;

use File;
use Artisan;
use Module;

trait ModuleConfig
{
    public static function bootModuleConfig()
    {
        debug('bootModuleConfig');
    }

    /**
     * 写入config
     * 
     * @param  string $module 模块名称
     * @param  array  $config 配置数组
     * @return boolean
     */
    private function config($module, array $config)
    {
        $module = Module::findOrFail($module);
        $module->setConfig($config);

        return true;
    }

    /**
     * 设置ENV
     * 
     * @param  string $key   键名，如：APP_ENV
     * @param  string $value 键值，如：local，如果为null，则为删除
     * @return bool
     */
    private function env($key, $value='')
    {
        $envs = [];

        if (is_string($key)) {
            $envs = [$key => $value];
        }

        if (is_array($key)) {
            $envs = array_merge($envs, $key);
        }

        foreach ($envs as $key => $value) {
            Artisan::call('env:set', [
                'key'   => $key,
                'value' => $value
            ]);   
        }

        // 清理配置和路由缓存
        Artisan::call('config:clear');        
        Artisan::call('route:clear'); 

        return $this;
    }
}
