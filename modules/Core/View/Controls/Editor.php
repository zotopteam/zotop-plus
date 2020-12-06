<?php

namespace Modules\Core\View\Controls;

use App\Support\Form\Control;

class Editor extends Control
{
    /**
     * 默认样式
     *
     * @var string
     */
    public static $defaultClass = 'form-control';

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
        $this->attributes->set('rows', 18, false);

        return $this->view('core::controls.editor');
    }
}
