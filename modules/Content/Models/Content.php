<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\UserRelation;
use Modules\Content\Support\Modelable;
use Modules\Content\Extend\Extendable;
use Module;

class Content extends Model
{
	use UserRelation, Extendable, Modelable;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'content';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['parent_id','model_id','title','title_style','slug','image','keywords','summary','link','view','hits','comments','status','stick','sort','user_id','source_id','publish_at'];
	
	
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];
	
	
    /**
     * 属性转换
     *
     * @var array
     */
    //protected $casts = [];
	

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // 更新设置parent_id时，禁止为自身或者自身的子节点
        static::updating(function($content) {
            if ($content->parent_id && in_array($content->id, static::parentIds($content->parent_id, true))) {
                abort(403, trans('content::content.move.forbidden', [$content->title]));
                return false;
            }
        });

        // 保存前数据处理
        static::saving(function($content) {

            $content->slug       = $content->slug ?: null;
            $content->sort       = $content->sort ?: time();

            // 如果发布时，发布时间大于当前时间，则为定时发布
            if ($content->status == 'publish') {
                if ($content->publish_at && $content->publish_at > now()) {
                    $content->status = 'future';
                } else {
                    $content->publish_at = now();
                }
            }

            // 如果状态为定时，但是没有发布时间，或者发布时间小于当前时间，直接发布
            if ($content->status == 'future' && (!$content->publish_at || $content->publish_at < now())) {
                $content->status = 'publish';
                $content->publish_at = now();
            }

        });

        // 保存后处理数据
        static::saved(function($content) {

        });

        static::deleting(function($content) {
            if ($content->children()->count()) {
                abort(403, trans('content::content.delete.notempty'));
            }
        });
    }  

    /**
     * 关联的模型数据
     */
    public function model()
    {
        return $this->belongsTo('Modules\Content\Models\Model', 'model_id', 'id');
    }

    /**
     * 关联子级别
     * @return [type] [description]
     */
    public function children()
    {
        return $this->hasMany('Modules\Content\Models\Content', 'parent_id', 'id');
    }

    /**
     * 关联父级别
     * @return [type] [description]
     */
    public function parent()
    {
        return $this->belongsTo('Modules\Content\Models\Content', 'parent_id', 'id');
    }    

    /**
     * 获取内容的状态
     * 
     * @param  string $model_id 模型编号
     * @return array
     */
    public static function status()
    {
        static $status = [];

        if (empty($status)) {
            $status = Module::data('content::content.status');
        }

        return $status;
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
        if ($self) {
            $parentIds[] = $id;
        }

        if ($parentId = static::where('id', $id)->value('parent_id')) {
            static::parentIds($parentId, true, $parentIds);
        }

        return array_reverse($parentIds);                
    }

    /**
     * 获取节点的全部子节点编号
     * 
     * @param  mixed  $id         编号
     * @param  boolean $self      是否包含自身
     * @param  mixed   $model_id  子节点类型
     * @param  array   $childrenIds  传递自身
     * @return array
     */    
    public static function childrenIds($id, $self=false, $model_id=[], &$childrenIds=[])
    {
        if ($id && $self) {
            $childrenIds[] = $id;
        }

        // 递归获取子节点
        $children_ids = static::where('parent_id', $id)->whereSmart('model_id', $model_id)->pluck('id');

        foreach ($children_ids as $children_id) {
            static::childrenIds($children_id, true, $model_id, $childrenIds);
        }

        return $childrenIds;    
    }    

    /**
     * 获取路径
     * 
     * @param  int  $id    节点编号
     * @param  boolean $self  是否包含自身
     * @return Collection
     */
    public static function path($id, $self=true, &$paths=[])
    {
        if ($id && $content = static::find($id)) {

            if ($self) {
                $paths[] = $content;
            }

            static::path($content->parent_id, true, $paths);
        }

        return array_reverse($paths);
    }

    /**
     * 获取内容状态名称
     * @param  mixed $value
     * @return string
     */
    public function getStatusNameAttribute($value)
    {
        return array_get(static::status(), $this->status.'.name');
    }

    /**
     * 获取内容状态图标
     * @param  mixed $value
     * @return string
     */
    public function getStatusIconAttribute($value)
    {
        return array_get(static::status(), $this->status.'.icon');
    }

    /**
     * 获取内容数据源编号
     * @param  mixed $value
     * @return string
     */
    public function getSourceIdAttribute($value)
    {
        if ($value) {
            return $value;
        }

        static $source_id = null;

        if (empty($source_id)) {
            $source_id = md5(microtime(true));
        }
        
        return $source_id;
    }


    /**
     * 获取节点的一级节点编号，如果本身就是一级节点，返回自身
     * 
     * @param  int  $id 编号
     * @return int
     */
    public function getTopIdAttribute($value)
    {
        $parentIds = static::parentIds($this->id, true);

        return reset($parentIds);
    }

    /**
     * 获取内容的视图文件
     * @param  mixed $value
     * @return string
     */
    public function getViewAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if (isset($this->parent->models)) {
            return array_get($this->parent->models, $this->model_id.'.view');
        }

        return $this->model->view;
    }

    /**
     * 获取内容url地址
     * @param  mixed $value
     * @return string
     */
    public function getUrlAttribute($value)
    {
        if ($this->link) {
            return url($this->link);
        }

        if ($this->status == 'publish') {
            return $this->slug ? route('content.slug', $this->slug) : route('content.show', $this->id);
        }

        return route('content.preview', $this->id);
    }

    /**
     * 排序 ，查询结果按照stick(置顶)、sort(排序)和id(编号)倒序
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array sort 例如：stick desc,id desc
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query, $sort=null)
    {
        return $query->orderby('stick', 'desc')->orderby('sort', 'desc')->orderby('id', 'desc');
    }

    /**
     * 获取发布的信息
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish($query)
    {
        return $query->where('status', 'publish');
    }

    /**
     * 不更新时间戳
     * @return this
     */
    public function scopeWithoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }    
}
