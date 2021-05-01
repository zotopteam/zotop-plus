<?php

namespace Modules\Core\View\Controls;

use Zotop\Support\Form\Control;

class Toggle extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $name = null,
        $value = null,
        $enable = null,
        $disable = null,
        $class = null
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->enable = $enable ?? 1;
        $this->disable = $disable ?? 0;
        $this->class = $class ?? 'form-control-toggle-default';
    }

    /**
     * toggle 标签
     *
     * @return \Zotop\Support\Attribute
     * @author Chen Lei
     * @date 2020-12-06
     */
    public function getToggle()
    {
        return attribute([
            'type'    => 'checkbox',
            'class'   => 'toggle',
            'checked' => ($this->value == $this->enable),
        ])->addData([
            'enable'  => $this->enable,
            'disable' => $this->disable,
        ]);
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.toggle')->with('toggle', $this->getToggle());
    }
}
