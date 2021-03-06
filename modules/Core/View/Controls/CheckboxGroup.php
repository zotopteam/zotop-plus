<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;
use Illuminate\Support\Arr;

class CheckboxGroup extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value = null,
        $options = null,
        $name = null,
        $column = 0,
        $class = 'checkboxgroup-default'
    )
    {
        $this->name = $name;
        $this->column = $column;
        $this->class = $class;
        $this->options = Arr::wrap($options);
        $this->value = Arr::wrap($value);
    }

    /**
     * 多选
     *
     * @param $key
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function checkbox($key)
    {
        return $this->attributes->merge([
            'name'    => $this->name . '[]',
            'value'   => $key,
            'checked' => in_array($key, $this->value),
        ]);

    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.checkbox-group');
    }
}
