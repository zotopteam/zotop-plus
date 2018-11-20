<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

class ArticleModel extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var  string
     */
    protected $table = 'content_model_article2';

    /**
     * 可以被批量赋值的属性。
     *
     * @var  array
     */
    protected $fillable = ['id', 'content', 'author', 'source'];

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
