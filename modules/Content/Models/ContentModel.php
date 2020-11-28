<?php
namespace Modules\Content\Models;

use App\Support\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * 内容扩展模型基类，所有的扩展模型类均继承于此类
 */
abstract class ContentModel extends Model
{
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * id 字符串格式，关闭自动增长
     *
     * @var bool
     */    
    public $incrementing = false;

    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;
}
