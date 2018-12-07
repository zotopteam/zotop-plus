<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\UserRelation;
use Format;


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
    protected $fillable = ['parent_id','type','name','path','url','extension','mimetype','width','height','size','module','controller','action','field','data_id','user_id','sort'];
	
	
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

            // 文件夹排序在前面
            if ($media->type == 'folder') {
                $media->sort += pow(10,9);
            }
        });

        // 更新设置parent_id时，禁止为自身或者自身的子节点
        static::updating(function($media) {
            if ($media->parent_id && in_array($media->id, static::parentIds($media->parent_id, true))) {
                abort(403, trans('media::media.move.forbidden', [$media->name]));
                return false;
            }
        });

        static::deleting(function($media) {
            if ($media->children()->count()) {
                abort(403, trans('media::media.delete.notempty', [$media->name]));
            }
        });

        // 删除文件和文件的缩略图、预览图
        static::deleted(function($file) {

            //文件真实路径
            if ($file->path) {
                
                // 获取文件的真实路径 TODO:暂时放publick中，后续增加多位置存储
                $path = public_path($file->path);
                    
                // 预览图和缩略图位置
                $temp = md5($path);
                $temp = substr($temp, 0, 2).'/'.substr($temp, 2, 2).'/'.$temp;

                app('files')->deleteDirectory(public_path('previews/'.$temp));
                app('files')->delete($path);
            }
        });        
    }    

    /**
     * 关联子级别
     * @return [type] [description]
     */
    public function children()
    {
        return $this->hasMany('Modules\Media\Models\Media', 'parent_id', 'id');
    }

    /**
     * 关联父级别
     * @return [type] [description]
     */
    public function parent()
    {
        return $this->belongsTo('Modules\Media\Models\Media', 'parent_id', 'id');
    }

    /**
     * 排序 ，查询结果sort(排序)和id(编号)倒序
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query)
    {
        return $query->orderby('sort', 'desc')->orderby('id', 'desc');
    }            

    /**
     * 获取节点的全部父编号
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  array   $parentIds 传递自身
     * @return array
     */
    public static function parentIds($id, $self=false, &$parentIds=[])
    {
        static $instance = null;

        if (empty($instance)) {
            $instance = new static;
        }

        if ($id && $self) {
            $parentIds[] = $id;
        }

        if ($id && $parentId = $instance->where('id', $id)->value('parent_id')) {
            $instance->parentIds($parentId, true, $parentIds);
        }

        return array_reverse($parentIds);                
    }

    /**
     * 获取父级
     * 
     * @param  int  $id    节点编号
     * @param  boolean $self  是否包含自身
     * @return Collection
     */
    public static function parents($id, $self=false)
    {
        $parentIds    = static::parentIds($id, $self);
        $parentSorts = array_flip($parentIds);

        // 获取父级并排序
        if ($parentIds) {
            return (new static)->whereIn('id', $parentIds)->get()->sortBy(function($media) use($parentSorts) {
                return $parentSorts[$media->id];
            });
        }

        return collect([]);
    }

    /**
     * 获取友好的创建日期。
     *
     * @return string
     */
    public function getCreatedAtHumanAttribute()
    {
        return Format::date($this->created_at, 'datetime human');
    }


    /**
     * 获取友好的文件大小
     *
     * @return string
     */
    public function getSizeHumanAttribute()
    {
        return $this->size ? Format::size($this->size) : null;
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
