<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

/**
 * 内容扩展模型：产品 product
 * 自动创建时间：2018-11-23 15:40:17
 */
class ProductModel extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var  string
     */
    protected $table = 'content_model_product';

    /**
     * 可以被批量赋值的属性。
     *
     * @var  array
     */
    protected $fillable = ['id', 'option'];

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
