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
    protected $fillable = ['modelid','control','label','name','type','length','default','notnull','unique','settings','tips','base','post','search','system','listorder','disabled'];
	
	
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
