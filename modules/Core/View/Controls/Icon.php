<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;
use Illuminate\Support\Arr;

class Icon extends Control
{
    /**
     * Create a new control instance.
     *
     * @param string|null $id
     * @param string|null $name
     * @param string|null $value
     * @param array $options
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = null,
        $options = []
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->options = Arr::wrap($options);
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
            'icon'          => $this->value,
            'cols'          => 10,
            'rows'          => 5,
            'iconset'       => 'fontawesome5',
            'selectedClass' => 'btn-success',
        ];

        // 标签上取出的属性
        $attributes = $this->attributes->pull(array_keys($default));

        // 设置属性
        $options = attribute($options)->merge($default, false)->merge($attributes->toArray());

        return $options->toArray();
    }

    /**
     * 启动 Icon
     *
     * @author Chen Lei
     * @date 2020-12-15
     */
    public function bootIcon()
    {
        $this->options = $this->options($this->options);
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.icon');
    }
}
