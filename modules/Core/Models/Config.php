<?php

namespace Modules\Core\Models;

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
                    $config[$item->module][$item->key] = static::tranform($item->value, $item->type, false);
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
     * @return mixed
     */
    public static function set($module, array $config)
    {
        
        $module = strtolower($module);

        foreach ($config as $key => $value) {
            
            $key  = strtolower($key);
            
            // 更新或者插入 Laravel不支持联合主键
            if ($item = static::where(['key'=>$key, 'module'=>$module])->first()) {

                // 编码数据
                $value = static::tranform($value, $item->type, true);                

                static::where(['key'=>$key, 'module'=>$module])->update(['value'=>$value]);
            } else {

                // 获取变量类型
                $type  = gettype($value);

                // 编码数据
                $value = static::tranform($value, $type, true);

                static::insert(['key' => $key, 'module' => $module,'value'=>$value, 'type'=>$type]);
            }

        }

        Cache::forget(static::CACHE_NAME);

        return true;          
    }

    /**
     * 删除配置项
     * 
     * @param string $module 模块名称
     * @param string $key 键名
     * @return mixed
     */
    public static function forget($module, $key=null)
    {
        if ($key) {
            static::where(['key'=>$key, 'module'=>$module])->delete();
        } else {
            static::where(['module'=>$module])->delete();
        }

        Cache::forget(static::CACHE_NAME);

        return true;            
    }

    /**
     * 编码或者解码数据
     * 
     * @param  mixed $value  数据
     * @param  string $type  类型
     * @param  bool $encode 转换或者翻转
     * @return mixed
     */
    public static function tranform($value, $type, $encode=true)
    {
        switch (strtolower($type)) {
            case 'int':
            case 'integer':
                $value = (int)$value;
                break;
            case 'real':
            case 'float':
            case 'double':
                $value = (float)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
            case 'boolean':
                $value = (bool)$value;
                break;
            case 'array':
                $value = $encode ? json_encode($value) : json_decode($value,true);
                break;                        
            default:                            
                break;
        }        

        return $value;
    }

}
