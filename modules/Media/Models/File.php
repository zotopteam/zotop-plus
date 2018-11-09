<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Media\Models\Media;

class File extends Media
{
    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();    
    
        // auto-sets values on creation
        static::creating(function ($query) {
            $query->sort = time();
        });

        static::addGlobalScope('file', function (Builder $builder) {
            $builder->where('type', '!=', 'folder');
        });                

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
}
