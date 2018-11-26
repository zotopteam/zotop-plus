<?php
namespace Modules\Content\Hook;

use Route;
use Modules\Content\Models\Content;

class Listener
{
    /**
     * 后台开始菜单扩展
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
     * @param  array $types 当前字段类型
     * @param  array $args 参数
     * @return array
     */
    public function types($types, $args)
    {
        if (in_array($args['model_id'], ['category'])) {
            $types['models'] = [
                'name'     => trans('content::field.type.models'),
                'view'     => '',
                'method'   => 'text',
                'cast'     => 'array',
                'settings' => ['required'=>1]
            ];
        }

        return $types;
    }

    public function contentManage($manage, $content)
    {
        // 查看和预览
        $manage['view'] = [
            'text' => $content->status == 'publish' ? trans('content::content.view') : trans('content::content.preview'),
            'href' => $content->url,
            'icon' => 'fas fa-eye',
            'attr' => ['target' => '_blank']
        ];

        // 下级节点
        if ($content->model->nestable) {
            $manage['nestable'] = [
                'text' => trans('content::content.children'),
                'href' => route('content.content.index', [$content->id]),
                'icon' => 'fas fa-folder-open text-warning',
            ];
        }

        // 修改
        $manage['edit'] = [
            'text' => trans('core::master.edit'),
            'href' => route('content.content.edit', [$content->id]),
            'icon' => 'fas fa-edit',
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

        $manage['sort'] = [
            'text'  => trans('content::content.sort'),
            'href'  => route('content.content.sort', ['parent_id'=>$content->parent_id, 'id'=>$content->id]),
            'icon'  => 'fa fa-sort-amount-up',
            'class' => 'js-open',
            'attr'  => ['data-width' => '80%','data-height' => '80%']
        ];        


        foreach (Content::status() as $status => $value) {
            if ($status == $content->status) {
                continue;
            }
            $manage[$status] = [
                'text'  => $value['name'],
                'href'  => route('content.content.status', [$content->id, $status]),
                'icon'  => $value['icon'],
                'class' => $status == 'future' ? 'js-future' : 'js-post',
            ];              
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
