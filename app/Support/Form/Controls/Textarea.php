<?php

namespace App\Support\Form\Controls;

use App\Support\Form\Control;

class Textarea extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $value = '',
        $cols = null,
        $rows = null
    )
    {
        $this->value = $value;
        $this->cols = $cols;
        $this->rows = $rows;
    }

    /**
     * 启动控件
     *
     * @author Chen Lei
     * @date 2020-12-18
     */
    public function bootTextArea()
    {
        $this->cols = is_int($this->cols) ? max($this->cols, 1) : 80;
        $this->rows = is_int($this->rows) ? max($this->rows, 1) : 10;
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
            'cols' => $this->cols,
            'rows' => $this->rows,
        ]);

        return $this->toHtmlString('<textarea ' . $this->attributes . ' >' . $this->value . '</textarea>');
    }
}
