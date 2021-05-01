<?php

namespace Modules\Content\View\Controls;

use Zotop\Support\Form\Controls\Textarea;

class ContentSummary extends Textarea
{
    /**
     * 启动控件
     *
     * @author Chen Lei
     * @date 2020-12-18
     */
    public function bootContentSummary()
    {
        $this->rows = is_int($this->rows) ? max($this->rows, 1) : 4;
    }
}
