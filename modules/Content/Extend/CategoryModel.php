<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

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
