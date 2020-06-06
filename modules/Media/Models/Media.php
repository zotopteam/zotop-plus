<?php

namespace Modules\Media\Models;

use Format;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Traits\UserRelation;


class Media extends Model
{
    use UserRelation;

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
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // 创建时保存排序，文件夹在最前面
        static::creating(function ($media) {
            $media->sort = $media->sort ?: time();
        });

        // 更新设置parent_id时，禁止为自身或者自身的子节点
        static::updating(function ($media) {

            // 移动文件或者目录时，禁止移动到自身或者自身的子目录下面
            if ($media->parent_id && $media->isDirty('parent_id')) {
                $parents = static::find($media->parent_id)->parents;
                if (in_array($media->id, array_keys($parents))) {
                    abort(403, trans('media::media.move.forbidden', [$media->name]));
                }
            }
        });

        static::deleting(function ($media) {
            if ($media->children()->count()) {
                abort(403, trans('media::media.delete.notempty', [$media->name]));
            }
        });

        // 删除文件和文件的缩略图、预览图
        static::deleted(function ($file) {
            // 删除记录的同时删除本地文件
            if ($file->disk && $file->path) {
                // 获取文件的真实路径 TODO:暂时放publick中，后续增加多位置存储
                Storage::disk($file->disk)->delete($file->path);
                // 删除预览图和缩略图位置
                // todo……
            }
        });
    }

    /**
     * 关联子级别
     * @return hasMany
     */
    public function child()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * 关联递归子级别
     * @return hasMany
     */
    public function children()
    {
        return $this->child()->with('children');
    }

    /**
     * 关联父级别
     * @return belongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    /**
     * 关联递归父级别
     * @return belongsTo
     */
    public function parents()
    {
        return $this->parent()->with('parents');
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
     * 获取全部父级数组
     *
     * @return array
     */
    public function getParentsAttribute()
    {
        $parents[$this->id] = $this;

        $parent_id = $this->parent_id;

        // 递归查询父级
        while (true) {
            if ($parent = $this->find($parent_id, ['id', 'parent_id', 'name'])) {
                $parents[$parent->id] = $parent;
                if ($parent_id = $parent->parent_id) {
                    continue;
                }
            }
            break;;
        }

        return array_reverse($parents, true);
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

    /**
     * 判定文件类型
     * @param  mixed $type 类型
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
