<?php
namespace Modules\Content\Hook;

use Route;
use Modules\Content\Models\Content as ContentModel;

class Content
{
    /**
     * 单条内容管理菜单
     * 
     * @param  array $manage  菜单项
     * @param  model $content 内容数据
     * @return array
     */
    public function hit($content)
    {
        ContentModel::WithoutTimestamps()->where('id', $content->id)->increment('hits');
        return $content;
    }    
}
