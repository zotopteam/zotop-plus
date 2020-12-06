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
        $cols = 80,
        $rows = 8
    )
    {
        $this->value = $value;
        $this->cols = is_int($cols) ? max($cols, 1) : 80;
        $this->rows = is_int($rows) ? max($rows, 1) : 10;
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
