<?php

namespace Modules\Core\Traits;

use File;
use Artisan;
use Module;
use Modules\Core\Models\Config;

trait ModuleConfig
{
    /**
     * 写入config
     * 
     * @param  [type] $namespace [description]
     * @param  array  $config    [description]
     * @return [type]            [description]
     */
    private function config($module, array $config)
    {
        // 获取当前配置，过滤掉当前模块配置中不存在的项      
        $configOriginal = [];

        $configFilePath = Module::getModulePath($module).'/config.php';

        if (File::exists($configFilePath)) {
           $configOriginal = include($configFilePath); 
        }

        $config = array_only($config, array_keys($configOriginal));

        Config::set($module, $config);

        return true;
    }

    /**
     * 设置ENV
     * 
     * @param  string $key   键名，如：APP_ENV
     * @param  string $value 键值，如：local，如果为null，则为删除
     * @return bool
     */
    private function setenv($key, $value='')
    {
        $envs = [];

        if (is_string($key)) {
            $envs = [$key => $value];
        }

        if (is_array($key)) {
            $envs = array_merge($envs, $key);
        }

        foreach ($envs as $key => $value) {
            Artisan::call('env:set',['key' => strtoupper($key), 'value'=>$value]);        
        }

        // 重启
        Artisan::call('reboot');           

        return $this;
    }
}
