<?php

namespace Modules\Content\Models;

use Module;
use App\Traits\Nestable;
use Illuminate\Support\Arr;
use App\Traits\UserRelation;
use Modules\Content\Extend\Extendable;
use Modules\Content\Support\Modelable;
use App\Support\Eloquent\Model;

class Content extends Model
{
    use UserRelation, Nestable, Extendable, Modelable;

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
    protected $fillable = ['parent_id', 'model_id', 'title', 'title_style', 'slug', 'image', 'keywords', 'summary', 'link', 'view', 'hits', 'comments', 'status', 'stick', 'sort', 'user_id', 'source_id', 'publish_at'];


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
     * booted
     * 
     * @return void
     */
    protected static function booted()
    {
        // 保存前数据处理
        static::saving(function ($content) {

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
        static::saved(function ($content) {
        });

        static::deleting(function ($content) {
            if ($content->child()->count()) {
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
     * 获取内容状态名称
     * @param  mixed $value
     * @return string
     */
    public function getStatusNameAttribute($value)
    {
        return Arr::get(static::status(), $this->status . '.name');
    }

    /**
     * 获取内容状态图标
     * @param  mixed $value
     * @return string
     */
    public function getStatusIconAttribute($value)
    {
        return Arr::get(static::status(), $this->status . '.icon');
    }

    /**
     * 获取内容状态颜色
     * @param  mixed $value
     * @return string
     */
    public function getStatusColorAttribute($value)
    {
        return Arr::get(static::status(), $this->status . '.color', 'info');
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
            return Arr::get($this->parent->models, $this->model_id . '.view');
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
    public function scopeSort($query, $sort = null)
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
