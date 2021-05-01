<?php

namespace Zotop\Modules\Traits;

use Zotop\Modules\Facades\Module;
use Illuminate\Support\Facades\Artisan;


trait ModuleConfig
{
    /**
     * boot
     *
     * @author Chen Lei
     * @date 2020-11-07
     */
    public static function bootModuleConfig()
    {
        debug('bootModuleConfig');
    }

    /**
     * 写入config
     *
     * @param string $module 模块名称
     * @param array $config 配置数组
     * @return boolean
     */
    private function config(string $module, array $config)
    {
        $module = Module::findOrFail($module);
        $module->setConfig($config);

        return true;
    }

    /**
     * 设置ENV
     *
     * @param mixed $key 键名，如：APP_ENV
     * @param string $value 键值，如：local，如果为null，则为删除
     * @return $this
     */
    private function env($key, $value = '')
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
                'value' => $value,
            ]);
        }

        // 清理配置和路由缓存
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        return $this;
    }
}
