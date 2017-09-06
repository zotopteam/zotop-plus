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
    public function save($namespace, array $config)
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


}
