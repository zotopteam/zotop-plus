<?php

namespace Modules\Media\Models;

use App\Support\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Support\Eloquent\Traits\Nestable;
use App\Support\Eloquent\Traits\UserRelation;


class Media extends Model
{
    use UserRelation, Nestable;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'media';


    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'is_folder', 'disk', 'type', 'name', 'path', 'hash', 'url', 'extension', 'mimetype', 'width', 'height', 'size', 'module', 'controller', 'action', 'field', 'source_id', 'user_id', 'sort'];


    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * booted
     * 
     * @return void
     */
    protected static function booted()
    {
        // 创建时保存排序
        static::creating(function ($media) {
            $media->sort = $media->sort ?: time();
        });


        // 删除后删除文件
        static::deleted(function ($media) {
            if ($media->disk && $media->path) {
                Storage::disk($media->disk)->delete($media->path);
            }
        });
    }

    /**
     * 排序 ，查询结果is_folder(是否文件夹), sort(排序)和id(编号)倒序
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query, $sort = null)
    {
        return $query->orderby('is_folder', 'desc')->orderby('sort', 'desc')->orderby('id', 'desc');
    }

    /**
     * 获取友好的创建日期。
     *
     * @return string
     */
    public function getCreatedAtHumanAttribute()
    {
        // 15 天前的直接显示时间
        if ($this->created_at->addDays(15) < now()) {
            return $this->created_at;
        }

        return $this->created_at->diffForHumans();
    }

    /**
     * 输出磁盘的完整路径，盘符:路径，如：public:images/2020/06/abc.jpg
     *
     * @return string
     */
    public function getDiskPathAttribute()
    {
        if ($this->disk && $this->path) {
            return $this->disk . ':' . $this->path;
        }
        return null;
    }

    /**
     * 获取友好的文件大小
     *
     * @return string
     */
    public function getSizeHumanAttribute()
    {
        return $this->size ? size_format($this->size) : null;
    }

    /**
     * 获取友好的图标
     *
     * @return string
     */
    public function getIconAttribute()
    {
        return app('files')->icon($this->extension, $this->type);
    }
}
