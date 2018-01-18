<?php

namespace Modules\Media\Models;

use Format;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'media_files';    
    protected $fillable = ['folder_id','name','path','url','type','extension','mimetype','width','height','size','module','controller','action','field','data_id','user_id'];

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();    
    
        // 删除文件和文件的缩略图、预览图
        static::deleted(function($file) {
            //文件真实路径
            $path = $file->realpath();
            
            // 预览图和缩略图位置
            $temp = md5($path);
            $temp = substr($temp, 0, 2).'/'.substr($temp, 2, 2).'/'.$temp;

            app('files')->deleteDirectory(public_path('previews/'.$temp));
            app('files')->delete($path);
        });
    }

    /**
     * 获取文件的真实路径
     * @return string
     */
    public function realpath()
    {
        return public_path($this->path);
    }

    /**
     * 获取文件的URL
     * @return string
     */
    public function url()
    {
        return url($this->url);
    }

    /**
     * 获取预览图URL
     * 
     * @param  int $width 宽度
     * @param  int $height 高度
     * @param  bool $fit 
     * @return string
     */
    public function preview($width = null, $height = null, $fit = true)
    {
        return preview($this->realpath(), $width, $height, $fit);
    }

    /**
     * 获取文件图标
     * 
     * @return string
     */
    public function icon()
    {
        return app('files')->icon($this->extension, $this->type);
    }

    /**
     * 获取格式化后的文件大小
     * 
     * @return string
     */
    public function size()
    {
        return Format::size($this->size);
    }

    /**
     * 获取格式化后的时间
     * 
     * @return string
     */
    public function createdAt($human = false)
    {
        if ($human) {
            return Format::date($this->created_at, 'datetime human');
        }
        return Format::date($this->created_at, 'datetime');
    }



    /**
     * 判定文件类型
     * @param  mixed $modelid 模型编号
     * @return boolean
     */
    public function isType($type)
    {
        // 根据类型判断
        if ($this->type == $type) {
            return true;
        }

        return false;
    }

    /**
     * Dynamically pass missing methods to the user.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // isAdmin,isSuper,isMember
        if (starts_with($method, 'is')) {
            return $this->isType(strtolower(substr($method, 2)));
        }

        return parent::__call($method, $parameters);
    }       
}
