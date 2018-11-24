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
                'tips'  => trans('core::mine.edit.description'),
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
        // 下级节点
        if ($content->model->nestable) {
            $manage['nestable'] = [
                'text' => trans('content::content.children'),
                'herf' => route('content.content.index', [$content->id]),
                'icon' => 'fas fa-folder-open',
            ];
        }

        // 修改
        $manage['edit'] = [
            'text' => trans('core::master.edit'),
            'herf' => route('content.content.edit', [$content->id]),
            'icon' => 'fas fa-edit',
        ];

        // foreach (Content::status() as $status => $value) {
        //     if ($status == $content->status) {
        //         continue;
        //     }
        //     $manage[$status] = [
        //         'text'  => $value['name'],
        //         'herf'  => route('content.content.status', [$content->id, $status]),
        //         'icon'  => $value['icon'],
        //         'class' => 'js-post',
        //     ];              
        // }


        // 回收站中的数据可以永久删除
        if ($content->status == 'trash') {
            $manage['delete'] = [
                'text'     => trans('content::content.destroy'),
                'data-url' => route('content.content.destroy', [$content->id]),
                'icon'     => 'fa fa-times',
                'class'    => 'js-confirm',
            ];
        } else {

            // 置顶和取消置顶
            $manage['stick'] = $content->stick ? [
                'text'  => trans('content::content.unstick'),
                'herf'  => route('content.content.stick', [$content->id]),
                'icon'  => 'fa fa-arrow-circle-down',
                'class' => 'js-post',
            ] : [
                'text'  => trans('content::content.stick'),
                'herf'  => route('content.content.stick', [$content->id]),
                'icon'  => 'fa fa-arrow-circle-up',
                'class' => 'js-post',
            ];

            // 回收站           
            $manage['trash'] = [
                'text'     => trans('content::content.status.trash'),
                'data-url' => route('content.content.status', [$content->id, 'trash']),
                'icon'     => 'fa fa-trash-alt',
                'class'    => 'js-post',
            ];            
        }

        return $manage;
    }
}
