<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

/**
 * 内容扩展模型：分类 category
 * 自动创建时间：2018-12-03 10:31:06
 */
class CategoryModel extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var  string
     */
    protected $table = 'content_model_category';

    /**
     * 可以被批量赋值的属性。
     *
     * @var  array
     */
    protected $fillable = ['id', 'models'];

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
    protected $casts = ['models' => 'array'];
}
