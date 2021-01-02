<?php

namespace Modules\Translator\Hooks;

use App\Modules\Module;
use Illuminate\Support\Arr;


class Listener
{
    /**
     * 开始菜单扩展
     *
     * @param array $start 开始菜单数组
     * @return array
     */
    public function start(array $start)
    {
        $start['translator'] = [
            'text' => trans('translator::translator.title'),
            'href' => route('translator.config.index'),
            'icon' => 'fa fa-language bg-info text-white',
            'tips' => trans('translator::translator.description'),
        ];

        return $start;
    }

    /**
     * 扩展模块管理
     *
     * @param array $manage
     * @param \App\Modules\Module $module
     * @return array
     * @author Chen Lei
     * @date 2020-12-31
     */
    public function moduleManage(array $manage, Module $module)
    {
        if ($module->is('translator')) {
            $manage = Arr::prepend($manage, [
                'text' => trans('translator::config.title'),
                'href' => route('translator.config.index'),
                'icon' => 'fa fa-cog',
            ], 'translator_config');
        }

        return $manage;
    }

    /**
     * 扩展表单助手
     *
     * @param array $data
     * @param array $args
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-27
     */
    public function controls(array $controls, array $args)
    {
        $controls['translate'] = [
            'text'       => trans('translator::form.control.translate'),
            'href'       => route('developer.form.control', ['control' => 'translate']),
            'icon'       => 'fa fa-code',
            'examples'   => [
                'developer::form.control.examples.standard',
                'translator::form.control.examples.translate',
            ],
            'attributes' => [
                'developer::form.control.attributes.standard',
                'developer::form.control.attributes.placeholder',
                'developer::form.control.attributes.autocomplete',
                'translator::form.control.attributes.translate',
                'developer::form.control.attributes.required',
                'developer::form.control.attributes.maxlength-minlength',
            ],
        ];

        $controls['slug'] = [
            'text'       => trans('translator::form.control.slug'),
            'href'       => route('developer.form.control', ['control' => 'slug']),
            'icon'       => 'fab fa-markdown',
            'examples'   => [
                'developer::form.control.examples.standard',
                'translator::form.control.examples.slug',
            ],
            'attributes' => [
                'developer::form.control.attributes.standard',
                'developer::form.control.attributes.placeholder',
                'developer::form.control.attributes.autocomplete',
                'translator::form.control.attributes.slug',
                'developer::form.control.attributes.required',
                'developer::form.control.attributes.maxlength-minlength',
            ],
        ];

        return $controls;
    }
}
