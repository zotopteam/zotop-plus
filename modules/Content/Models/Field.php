<?php
namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
	
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'content_field';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['model_id','label','type','name','length','default','required','unique','settings','help','post','search','system','col','row','sort','disabled'];
	
	
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
    protected $casts = [
        'settings' => 'json'
    ];
	
	
    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    //public $timestamps = false;	
}
