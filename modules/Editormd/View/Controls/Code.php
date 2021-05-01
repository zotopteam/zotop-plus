<?php

namespace Modules\Editormd\View\Controls;

use Zotop\Modules\Facades\Module;
use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;

class Code extends Control
{
    /**
     * Create a new control instance.
     *
     * @param string|null $name
     * @param string|null $value
     * @param array $options
     */
    public function __construct(
        $name = null,
        $value = null,
        $options = []
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->options = Arr::wrap($options);
    }

    /**
     * 从属性中获取选项值，属性设置的选项值会覆盖选项中已经存在的项
     *
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function options()
    {
        $default = [
            'width'         => '100%',
            'height'        => 500,
            'placeholder'   => 'coding……',
            'mode'          => 'text/html',
            'watch'         => false,
            'toolbar'       => false,
            'codeFold'      => true,
            'searchReplace' => true,
            'autoHeight'    => false,
            'theme'         => 'default',
            'path'          => Module::asset('editormd:editormd/lib', false) . '/',
        ];

        // 标签上取出的属性
        $options = $this->attributes->pull(array_keys($default))->toArray();
        debug($this->attributes->toArray(), $options);
        // 合并标签属性和默认属性
        return attribute($this->options)
            ->merge($default, false)
            ->merge($options, true)
            ->toArray();
    }

    /**
     * 启动 Code
     *
     * @author Chen Lei
     * @date 2020-12-15
     */
    public function bootCode()
    {
        $this->options = $this->options();
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('editormd::controls.code');
    }
}
