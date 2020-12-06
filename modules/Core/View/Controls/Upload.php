<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;

class Upload extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value = null
    )
    {
        $this->value = $value;
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('core::controls.upload');
    }
}
