<?php

namespace Zotop\View\Form\Controls;

use Zotop\View\Form\Control;

class Input extends Control
{
    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            'type' => $this->type,
        ]);

        return $this->toHtmlString('<input ' . $this->attributes . ' />');
    }
}
