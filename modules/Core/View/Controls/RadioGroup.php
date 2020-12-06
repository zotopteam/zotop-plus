<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;
use Illuminate\Support\Arr;

class RadioGroup extends Control
{

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
        $class = 'radiogroup-default'
    )
    {
        $this->name = $name;
        $this->column = $column;
        $this->class = $class;
        $this->options = Arr::wrap($options);
        $this->value = $this->getValue($value);
    }

    /**
     * 取得value值，如果没有选择值，选择options的第一个
     *
     * @param mixed $value
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getValue($value)
    {
        if (is_null($value)) {
            $value = array_keys($this->options);
            $value = reset($value);
        }

        return $value;
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.radio-group');
    }
}
