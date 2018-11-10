<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Modules\Core\Traits\UserRelation;

class Model extends BaseModel
{
    use UserRelation;
	
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'content_model';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['id','icon','name','description','module','model','template','posts','sort','disabled','user_id'];
	
	
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];
	
	
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
     * id 字符串格式，关闭自动增长
     *
     * @var bool
     */    
    public $incrementing = false;
}
