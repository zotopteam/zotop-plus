<?php

namespace Modules\Translator\View\Controls;

use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;

class Translate extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $button = null,
        $options = []
    )
    {
        $this->button = $button;
        $this->options = Arr::wrap($options);
    }

    /**
     * 启动控件
     *
     * @author Chen Lei
     * @date 2020-12-21
     */
    public function bootTranslate()
    {
        $this->id = $this->attributes->get('id');
        $this->options = $this->options($this->options);
    }

    /**
     * options
     *
     * @param array $options
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function options(array $options)
    {
        $default = [
            'url'       => route('translator.translate'),
            'source'    => '',
            'format'    => '',
            'from'      => '',
            'to'        => '',
            'maxlength' => '',
            'separator' => '',
        ];

        // 标签上取出的属性
        $attributes = $this->attributes->pull(array_keys($default));

        // 设置属性
        $options = attribute($options)->merge($default, false)->merge($attributes->toArray());

        return $options->toArray();
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('translator::controls.translate');
    }
}
