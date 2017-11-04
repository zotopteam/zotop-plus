<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Events\ConfigCacheClear;


class Config extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * 定义填充字段
     * 
     * @var array
     */
    protected $fillable   = ['key','value','module'];
    
    /**
     * 禁止写入的字段
     *
     * @var array
     */
    protected $guarded    = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;    

    /**
     * 缓存键名
     *
     * @var string
     */
    const CACHE_NAME = 'config_database';

    /**
     * 缓存键名
     *
     * @var string
     */
    const CACHE_TIME = 1440;



    /**
     * 获取模块的配置
     * 
     * @param  string $module 模块名称
     * @return array
     */
    public static function get($module=null)
    {
        $config = [];

        if (empty($config)) {
            
            $config = Cache::remember(static::CACHE_NAME, static::CACHE_TIME, function() {
                
                $config = [];

                foreach (static::all() as $item) {

                    switch ($item->type) {
                        case 'int':
                        case 'integer':
                            $item->value = (int)$item->value;
                            break;
                        case 'real':
                        case 'float':
                        case 'double':
                            $item->value = (float)$item->value;
                            break;
                        case 'string':
                            $item->value = (string)$item->value;
                            break;
                        case 'bool':
                        case 'boolean':
                            $item->value = (bool)$item->value;
                            break;
                        case 'array':
                            $item->value = json_decode($item->value, true);
                            break;                        
                        default:                            
                            break;
                    }

                    $config[$item->module][$item->key] = $item->value;
                }

                return $config;
            });            
        }

        if ($module = strtolower($module)) {
            return isset($config[$module]) ? $config[$module] : [];
        }

        return $config;
    }

    /**
     * 设置模块的config
     * 
     * @param string $module 模块名称
     * @param array  $config 设置数组
     */
    public static function set($module, array $config)
    {
        foreach ($config as $key => $value) {
                 
            // 获取变量类型
            $type = gettype($value);

            // 数组转换
            if ($type=='array') {
                $value = json_encode($value);
            }
            
            // 更新或者插入 Laravel不支持联合主键
            if (static::where(['key'=>$key, 'module'=>$module])->first()) {
                static::where(['key'=>$key, 'module'=>$module])->update(['value'=>$value, 'type'=>$type]);
            } else {
                static::insert(['key' => $key, 'module' => $module,'value'=>$value, 'type'=>$type]);
            }

        }

        Cache::forget(static::CACHE_NAME);

        return true;          
    }

}
