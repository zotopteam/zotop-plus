<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

/**
 * 内容扩展模型：页面 page
 * 自动创建时间：2018-11-24 21:10:36
 */
class PageModel extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var  string
     */
    protected $table = 'content_model_page';

    /**
     * 可以被批量赋值的属性。
     *
     * @var  array
     */
    protected $fillable = ['id', 'content'];

    /**
     * 不可被批量赋值的属性。
     *
     * @var  array
     */
    protected $guarded = [];

    /**
     * 属性转换
     *
     * @var  array
     */
    protected $casts = [];
}
