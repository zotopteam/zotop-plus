<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Nestable;
use Format;

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

        // 更新设置parent_id时，禁止为自身或者自身的子节点
        static::updating(function($folder) {
            if ($folder->parent_id && in_array($folder->parent_id, $folder->getChildIds(true))) {
                $folder->error = trans('media::folder.move.forbidden', [$folder->name]);
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
}
