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
}
