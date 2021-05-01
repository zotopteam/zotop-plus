<?php

namespace Zotop\View\Form\Controls;

use Zotop\View\Attribute;
use Zotop\View\Form\Control;
use Illuminate\Support\Arr;

class Checkable extends Control
{

    /**
     * 字段默认的类名
     *
     * @var string
     */
    public static $defaultClass = 'form-control-check';

    /**
     * 默认标签样式
     *
     * @var string
     */
    public static $defaultLableClass = 'form-control-check-label';


    /**
     * Create a new control instance.
     *
     * @param null $id
     * @param null $label
     */
    public function __construct(
        $id = null,
        $value = null,
        $checked = null,
        $label = null
    )
    {
        $this->id = $id;
        $this->value = $value;
        $this->checked = $checked;
        $this->label = $label;
    }

    /**
     * 检查是否checked
     *
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function isChecked()
    {
        if ($this->originalAttributes->has('checked')) {
            return true;
        }

        // 获取标签中设置的value
        // checkbox 和 radio 与其他控件不同，真实值时checked之后才有效，默认传递进来的值已经被 completeAttributes 覆盖
        // 所以需要用绑定值和标签中的值来判断是否选择
        $value = $this->originalAttributes->get('value');

        if ($this->type == 'checkbox') {
            return in_array($value, Arr::wrap($this->bindValue)) ? true : null;
        }

        if ($this->type == 'radio') {
            return ($this->bindValue === $value) ? true : null;
        }

        return null;
    }

    /**
     * checkable label
     *
     * @return \Illuminate\Support\HtmlString|string
     * @author Chen Lei
     * @date 2020-12-05
     */
    protected function getLabel()
    {
        if (empty($this->label)) {
            return '';
        }

        $attributes = new Attribute([
            'class' => static::$defaultLableClass,
            'for'   => $this->id,
        ]);

        return $this->toHtmlString(' <label ' . $attributes . '>' . $this->label . '</label>');
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
        $this->attributes->merge([
            'type'    => $this->type,
            'id'      => $this->id,
            'value'   => $this->originalAttributes->get('value'),
            'checked' => $this->isChecked(),
        ]);

        return $this->toHtmlString('<input ' . $this->attributes . ' />' . $this->getLabel());
    }
}
