<?php

namespace Zotop\Support\Form\Controls;

use Zotop\Support\Attribute;
use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;

class Select extends Control
{
    /**
     * 默认placeholder文字
     *
     * @var string
     */
    public static $defaultPlaceholder = 'master.select.placeholder';


    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value,
        $options = [],
        $placeholder = '',
        $mutiple = false
    )
    {
        $this->value = $value;
        $this->options = Arr::Wrap($options);
        $this->placeholder = $placeholder ?: trans(static::$defaultPlaceholder);
        $this->mutiple = $mutiple ? true : false;
    }

    /**
     * 获取选项
     *
     * @author Chen Lei
     * @date 2020-12-05
     */
    public function getOptions()
    {
        $options = [];

        if ($this->placeholder) {
            $options[] = $this->convertToSelectOption('', $this->placeholder, $this->value, false);
        }

        foreach ($this->options as $value => $display) {
            if (is_array($display)) {
                $options[] = $this->convertToSelectOptionGroup($value, $display, $this->value, $this->multiple);
            } else {
                $options[] = $this->convertToSelectOption($value, $display, $this->value, $this->multiple);
            }
        }

        return implode(PHP_EOL, $options);
    }

    /**
     * 转换select的option
     *
     * @param mixed $value 选项值
     * @param string $display 选项显示内容
     * @param mixed $selected 选择的项
     * @param bool $multiple 是否多选
     * @return string
     */
    protected function convertToSelectOption($value, $display, $selected, $multiple)
    {
        if ($multiple) {
            $isSelected = in_array($value, Arr::wrap($selected));
        } else if (is_int($value) && is_bool($selected)) {
            $isSelected = (bool)$value === $selected;
        } else {
            $isSelected = (string)$value === (string)$selected;
        }

        $attributes = new Attribute([
            'selected' => $isSelected,
            'value'    => $value,
        ]);

        return $this->toHtmlString('<option ' . $attributes . '>' . e($display, false) . '</option>');
    }

    /**
     * 转换select的optgroup
     *
     * @param mixed $label 选项标签
     * @param array $options 选项组
     * @param mixed $selected 选择的项
     * @param bool $multiple 是否多选
     * @return string
     */
    protected function convertToSelectOptionGroup($label, $options, $selected, $multiple, $level = 0)
    {
        $space = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);

        $attributes = new Attribute([
            'label' => $space . $label,
        ]);

        $inner = [];

        foreach ($options as $value => $display) {
            if (is_array($display)) {
                $inner[] = $this->convertToSelectOptionGroup($value, $display, $selected, $multiple, $level + 1);
            } else {
                $inner[] = $this->convertToSelectOption($value, $display, $selected, $multiple);
            }
        }

        return $this->toHtmlString('<optgroup ' . $attributes . '>' . implode(PHP_EOL, $inner) . '</optgroup>');
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     * @author Chen Lei
     * @date 2020-12-03
     */
    public function render()
    {
        return $this->toHtmlString('<select ' . $this->attributes . ' >' . $this->getOptions() . '</select>');
    }
}
