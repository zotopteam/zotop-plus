<?php

namespace Modules\Content\View\Controls;

use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;

class ContentTitle extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $options = []
    )
    {
        $this->options = Arr::wrap($options);
    }

    /**
     * 控件启动
     *
     * @author Chen Lei
     * @date 2020-12-18
     */
    public function bootContentTitle()
    {
        // 获取id
        $this->id = $this->attributes->get('id');

        // 样式控件
        $this->styleName = $this->attributes->get('name') . '_style';
        $this->styleValue = $this->form->getBind($this->styleName);
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public
    function render()
    {
        return $this->view('content::controls.content-title');
    }
}
