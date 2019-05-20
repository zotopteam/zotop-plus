<?php
namespace Modules\Content\Hook;

use Route;
use Modules\Content\Models\Content;

class Listener
{
    /**
     * 后台开始菜单扩展
     * 
     * @param  array $start 已有开始菜单
     * @return array
     */
    public function start($start)
    {
        //编辑我的资料
        if (allow('content.index')) {
            $start['content-index'] = [
                'text'  => trans('content::content.title'),
                'href'  => route('content.content.index'),
                'icon'  => 'fa fa-newspaper bg-success text-white', 
                'tips'  => trans('content::content.description'),
            ];
        }
      
        return $start;
    }

    /**
     * 后台快捷导航扩展
     * 
     * @param  array $start 已有快捷导航
     * @return array
     */
    public function navbar($navbar)
    {
        // 主页
        $navbar['content-index'] = [
            'text'  => trans('content::content.title'),
            'href'  => route('content.content.index'),
            'active' => Route::is('content.*')
        ];

        return $navbar;
    }

    /**
     * 字段类型滤器
     * 
     * @param  array $types 当前字段类型
     * @param  array $args 参数
     * @return array
     */
    public function types($types, $args)
    {
        // models 设置为全局可用
        $types['models'] = [
            'name'     => trans('content::field.type.models'),
            'view'     => '',
            'method'   => 'text',
            'cast'     => 'array',
            'settings' => ['required'=>1]
        ];     

        return $types;
    }

    /**
     * 单条内容管理菜单
     * 
     * @param  array $manage  菜单项
     * @param  model $content 内容数据
     * @return array
     */
    public function contentManage($manage, $content)
    {
        // 查看和预览
        $manage['view'] = [
            'text'  => $content->status == 'publish' ? trans('content::content.view') : trans('content::content.preview'),
            'href'  => $content->url,
            'icon'  => 'fas fa-eye',
            'attrs' => ['target' => '_blank']
        ];

        // 修改
        $manage['edit'] = [
            'text' => trans('core::master.edit'),
            'href' => route('content.content.edit', [$content->id]),
            'icon' => 'fas fa-edit',
        ];

        // 复制
        $manage['duplicate'] = [
            'text'  => trans('core::master.duplicate'),
            'href' => route('content.content.duplicate', [$content->id]),            
            'icon'  => 'fas fa-copy',
        ];        

        // 移动
        $manage['move'] = [
            'text'  => trans('core::master.move'),
            'icon'  => 'fas fa-arrows-alt',
            'class' => 'js-move',
            'attrs' => ['data-id' => $content->id, 'data-parent_id' => $content->parent_id]
        ];

        // 排序
        $manage['sort'] = [
            'text'  => trans('content::content.sort'),
            'href'  => route('content.content.sort', ['parent_id'=>$content->parent_id, 'id'=>$content->id]),
            'icon'  => 'fa fa-sort-amount-up',
            'class' => 'js-open',
            'attrs' => ['data-width' => '80%','data-height' => '70%']
        ];        

        // 回收站中的数据可以永久删除
        if ($content->status == 'trash') {
            $manage['delete'] = [
                'text'     => trans('content::content.destroy'),
                'href' => route('content.content.destroy', [$content->id]),
                'icon'     => 'fa fa-times',
                'class'    => 'js-delete',
            ];
        }

        foreach (Content::status() as $status => $value) {
            // 不显示自身状态和定时发布状态，定时发布取决于发布时间，如果发布时，时间是未来时间，则自动判定为定时发布
            if ($status == $content->status || $status == 'future' ) {
                continue;
            }

            $manage[$status] = [
                'text'  => $value['name'],
                'href'  => route('content.content.status', [$status, $content->id]),
                'icon'  => $value['icon'],
                'class' => 'js-post',
            ];              
        }

        // 待审状态时，发布显示通过审核
        if ($content->status == 'pending') {
            $manage['publish']['text'] = trans('content::content.status.approved').'&'.$manage['publish']['text'];
        }

        // 如果状态是future或者发布，不显示发布按钮
        if ($content->status == 'future') {
            unset($manage['publish']);
        }

        // 置顶和取消置顶
        $manage['stick'] = $content->stick ? [
            'text'  => trans('content::content.unstick'),
            'href'  => route('content.content.stick', [$content->id]),
            'icon'  => 'fa fa-arrow-circle-down',
            'class' => 'js-post',
        ] : [
            'text'  => trans('content::content.stick'),
            'href'  => route('content.content.stick', [$content->id]),
            'icon'  => 'fa fa-arrow-circle-up',
            'class' => 'js-post',
        ];    

        return $manage;
    }   
}
