<?php
namespace Modules\Content\Extend;

trait Extendable
{
    
    /**
     * 获取内容表和模型[分类]的一对一关联
     * The relation of the model: category
     */
    public function CategoryRelation()
    {
        return $this->hasOne('Modules\Content\Extend\CategoryModel', 'id', 'id');
    }
    
    
    /**
     * 获取内容表和模型[页面]的一对一关联
     * The relation of the model: page
     */
    public function PageRelation()
    {
        return $this->hasOne('Modules\Content\Extend\PageModel', 'id', 'id');
    }
    
    
    /**
     * 获取内容表和模型[文章]的一对一关联
     * The relation of the model: article
     */
    public function ArticleRelation()
    {
        return $this->hasOne('Modules\Content\Extend\ArticleModel', 'id', 'id');
    }
    
    
}
