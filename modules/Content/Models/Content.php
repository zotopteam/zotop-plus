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
    protected $fillable = ['parent_id','model_id','title','title_style','slug','image','keywords','summary','url','template','hits','comments','status','stick','sort','user_id'];
	
	
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
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    //public $timestamps = false;

    /**
     * boot
     */
    public static function boot()
    {
        parent::boot();

        // 保存前数据处理
        static::saving(function($content) {
            $content->slug = $content->slug ?: null;
            $content->sort = $content->sort ?: time();

            // 发布和定时发布的时间必须大于当前时间，其他状态发布时间为空
            if (in_array($content->status, ['publish', 'future']) && $now = now()) {
                $content->publish_at = $content->publish_at > $now ? $content->publish_at : $now;
            } else {
                $content->publish_at = null;
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
        static $instance = null;

        if (empty($instance)) {
            $instance = new static;
        }

        if ($self) {
            $parentIds[] = $id;
        }

        if ($parentId = $instance->where('id', $id)->value('parent_id')) {
            $instance->parentIds($parentId, true, $parentIds);
        }

        return array_reverse($parentIds);                
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
     * 获取内容状态图标
     * @param  mixed $value
     * @return string
     */
    public function getDataIdAttribute($value)
    {
        return 'content-'.$this->id;
    }

    /**
     * 获取内容的视图文件
     * @param  mixed $value
     * @return string
     */
    public function getViewAttribute($value)
    {
        if ($this->template) {
            return $this->template;
        }

        if (isset($this->parent->models)) {
            return array_get($this->parent->models, $this->model_id.'.template');
        }

        return $this->model->template;
    }

    /**
     * 获取内容url地址
     * @param  mixed $value
     * @return string
     */
    public function getUrlAttribute($value)
    {
        if ($this->link) {
            return $this->link;
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort($query)
    {
        return $query->orderby('stick', 'desc')->orderby('sort', 'desc')->orderby('id', 'desc');
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

    public function scopeParents($query, $id, $self=false)
    {
        $parentIds = static::parentIds($id, $self);
        
        return $query->whereIn('id', $parentIds);
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
