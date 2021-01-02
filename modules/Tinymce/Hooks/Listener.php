<?php

namespace Modules\Tinymce\Hooks;

use Module;


class Listener
{
    /**
     * 编辑器属性
     *
     * @param string|array $options 属性数组
     * @param string $mode 编辑器模式
     * @return array
     */
    public function options($options, $attrs)
    {
        // 获取'full','standard','simple' 三种类型编辑器的属性数据
        if (is_string($options)) {
            $options = Module::data('tinymce::options.' . $options, $attrs);
        }

        // 加载默认属性，补全编辑器属性
        $options = array_merge(
            Module::data('tinymce::options.default', $attrs),
            (array)$options
        );

        return $options;
    }

    /**
     * 最佳自定义工具条
     *
     * @param array $options 扩展属性
     * @param array $attrs 标签属性
     * @return array
     */
    public function tools($options, $attrs)
    {
        // 传递给编辑器的tools数组
        $options['tools'] = [];

        // 从编辑器设置获取可显示的tools，多个之前用空格隔开，如果没有设置，则显示全部tools
        $show = isset($attrs['tools']) ? explode(' ', $attrs['tools']) : [];

        // 加载tools，如果单个模块或者部分功能只允许加载部分tool，则通过hook实现
        $tools = Module::data('tinymce::tools', $attrs);

        // 从编辑器表单参数获取可显示的tools，多个之前用空格隔开，如果没有设置，则显示全部tools
        if (isset($attrs['tools'])) {
            $show = $attrs['tools'] ? explode(' ', $attrs['tools']) : [];
            $tools = $show ? Arr::only($tools, $show) : [];
        }

        // 设置编辑器参数
        if ($tools) {
            // 加载tools，如果单个模块或者部分功能只允许加载部分tool，则通过hook实现
            $options['tools'] = $tools;
            // 加载tools插件
            $options['plugins'] = $options['plugins'] . ' tools';
            // 追加tools按钮
            if (stripos(' ' . $options['toolbar'] . ' ', ' tools ') === false) {
                $options['toolbar'] = $options['toolbar'] . ' tools';
            }
        } else {
            $options['toolbar'] = str_replace(' tools ', '', ' ' . $options['toolbar'] . ' ');
        }

        return $options;
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
        $controls['editor'] = [
            'text'       => trans('developer::form.control.editor'),
            'href'       => route('developer.form.control', ['control' => 'editor']),
            'icon'       => 'fa fa-code',
            'examples'   => [
                'tinymce::form.control.examples.editor',
            ],
            'attributes' => [
                'developer::form.control.attributes.standard',
                'developer::form.control.attributes.placeholder',
                'tinymce::form.control.attributes.editor',
            ],
        ];


        return $controls;
    }
}
