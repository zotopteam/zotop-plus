<?php
namespace Modules\Content\Extend;

use Modules\Content\Models\ContentModel as ContentModel;

/**
 * 内容扩展模型：文章 article
 * 自动创建时间：2018-12-03 00:37:58
 */
class ArticleModel extends ContentModel
{
    /**
     * 与模型关联的数据表。
     *
     * @var  string
     */
    protected $table = 'content_model_article';

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
