<?php
namespace Modules\Content\Hook;

use Route;

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
                'settings' => ['required'=>1],
            ];
        }

        return $types;
    }
}
