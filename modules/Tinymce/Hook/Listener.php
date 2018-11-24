<?php
namespace Modules\Tinymce\Hook;

use Module;
use Filter;


class Listener
{
    /**
     * 编辑器属性
     * 
     * @param  array $options 属性数组
     * @param  string $mode 编辑器模式
     * @return array
     */    
    public function options($options, $attrs)
    {
        // 获取'full','standard','simple' 三种类型编辑器的属性数据
        if (is_string($options)) {
            $options = Module::data('tinymce::options.'.$options, $attrs);
        }

        // 加载默认属性，补全编辑器属性
        $options = array_merge(
            Module::data('tinymce::options.default', $attrs),
            $options
        );

        return $options;
    }

    /**
     * 最佳自定义工具条
     * @param  array $options 扩展属性
     * @param  array $attrs 标签属性
     * @return array
     */
    public function tools($options, $attrs)
    {
        $tools = isset($attrs['tools']) ? $attrs['tools'] : $options['tools'];

        $options['tools'] = [];

        if ($tools) {

            // 完整的tools数组
            if (is_array($tools)) {
                $options['tools'] = $tools;
            }

            // 加载定义的tools
            if (is_string($tools)) {
                $options['tools'] = Module::data('tinymce::tools.'.$tools, $attrs);
            }

            // 加载tools插件
            $options['plugins'] = $options['plugins'].' zotop_tools';

            // 加载tools按钮
            foreach (array_keys($options['tools']) as $button) {
                if (stripos(' '.$options['toolbar'].' ', ' '.$button.' ') === false) {
                    $options['toolbar'] = $options['toolbar'].' '.$button;
                }
            }
        }

        return $options;
    }
}
