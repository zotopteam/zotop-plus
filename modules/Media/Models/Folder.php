<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Nestable;

class Folder extends Model
{
    use Nestable;
    
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'media_folders';    
    protected $fillable = ['parent_id','name','settings','sort','disabled','created_at','updated_at'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();    
    
        // 为安全考虑，禁止删除非空文件夹
        static::deleting(function($folder) {
            
            if ($folder->files()->count()) {
                $folder->error = trans('media::folder.delete.notempty',[$folder->name]);
                return false;
            }

            if ($folder->subfolder()->count()) {
                $folder->error = trans('media::folder.delete.notempty',[$folder->name]);
                return false;
            }

        });
    }    

    /**
     * 关联文件夹的文件
     */
    public function files()
    {
        return $this->hasMany('Modules\Media\Models\File', 'folder_id', 'id');
    }  

    /**
     * 关联子文件夹
     */
    public function subfolder()
    {
        return $this->hasMany('Modules\Media\Models\Folder', 'parent_id', 'id');
    }
}
