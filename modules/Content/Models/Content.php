<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\UserRelation;

class Content extends Model
{
	use UserRelation;

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
    protected $fillable = ['parent_id','model_id','title','title_style','url','image','keywords','summary','template','hits','comments','status','stick','sort','user_id'];
	
	
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
    //
    
    /**
     * 获取父级数据
     * 
     * @param  int $id 父级别编号
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    private function parent($id)
    {
        $parent = static::findOrNew($id);

        if (! $parent->exists) {
            $parent->id    = $id;
            $parent->title = trans('content::content.root');
        }

        return $parent;
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
