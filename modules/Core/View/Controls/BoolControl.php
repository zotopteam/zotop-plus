<?php

namespace Modules\Core\View\Controls;

use Illuminate\Support\Str;

class BoolControl extends RadioGroup
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
        $class = null
    )
    {
        $this->name = $name;
        $this->column = $column;
        $this->class = $class;
        $this->options = $options;
        $this->value = $this->getValue($value);
    }

    /**
     * Bool 选项
     *
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function optionsBool()
    {
        return [
            1 => trans('master.yes'),
            0 => trans('master.no'),
        ];
    }

    /**
     * Bool 选项
     *
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function optionsEnable()
    {
        return [
            1 => trans('master.enable'),
            0 => trans('master.disable'),
        ];
    }

    /**
     * 取得value值，如果没有选择值，选择options的第一个
     *
     * @param mixed $value
     * @param int $default
     * @return mixed
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getValue($value, $default = 1)
    {
        return (is_null($value) || !in_array($value, [0, 1]))
            ? $default
            : $value;
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        $this->class = $this->class ?? "radiogroup-{$this->type}";
        $this->options = $this->{'options' . Str::studly($this->type)}();

        return $this->view('core::controls.radio-group');
    }
}
