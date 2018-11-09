<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\Media\Models\Media;
use Modules\Core\Traits\Nestable;

class Folder extends Media
{
    use Nestable;

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->type = 'folder';
            $query->sort = time() + pow(10,9);
        });

        static::addGlobalScope('folder', function (Builder $builder) {
            $builder->where('type', '=', 'folder');
        });             
    
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
        return $this->hasMany('Modules\Media\Models\File', 'parent_id', 'id');
    }  

    /**
     * 关联子文件夹
     */
    public function subfolder()
    {
        return $this->hasMany('Modules\Media\Models\Folder', 'parent_id', 'id');
    }

}
