<?php

namespace Modules\Media\Models;

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
            $path = $file->getRealPath();
            
            // 预览图和缩略图位置
            $temp = md5($path);
            $temp = substr($temp, 0, 2).'/'.substr($temp, 2, 2).'/'.$temp;

            app('files')->deleteDirectory(public_path('temp/preview/'.$temp));
            app('files')->delete($path);
        });
    }

    /**
     * 获取文件的真实路径
     * @return string
     */
    public function getRealPath()
    {
        return public_path($this->path);
    }

    /**
     * 获取预览图
     * 
     * @param  int $width 宽度
     * @param  int $height 高度
     * @param  bool $fit 
     * @return string
     */
    public function getPreview($width = null, $height = null, $fit = true)
    {
        return preview($this->getRealPath(), $width, $height, $fit);
    }     
}
