<?php

namespace Modules\Core\Traits;

use File;
use Artisan;

trait ModuleConfig
{
    /**
     * 写入config
     * 
     * @param  [type] $namespace [description]
     * @param  array  $config    [description]
     * @return [type]            [description]
     */
    private function save($namespace, array $config)
    {
        // 当前配置
        $current = config($namespace, []);

        // 合并配置，只合并已经存在的值
        $config = array_merge($current, array_only($config, array_keys($current)));

        // 写入
        $path = config_path(str_replace('.', DIRECTORY_SEPARATOR, $namespace).'.php');
        
        // 如果不存在，尝试创建
        if (!File::isDirectory($dir = dirname($path))) {
            File::makeDirectory($dir, 0775, true);
        }         

        File::put($path, "<?php\nreturn ".var_export($config,true).";");

        // 如果是本地或者测试模式或者处于debug状态下，不缓存路由和配置
        if ( app()->environment('local','testing') OR config('app.debug') ) {
            
            // 清除配置缓存
            Artisan::call('config:clear');

        } else {

            // 重建配置缓存
            Artisan::call('config:cache');
        }        

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

        Artisan::call('config:cache');
        Artisan::call('route:cache');                

        return $this;
    }
}
