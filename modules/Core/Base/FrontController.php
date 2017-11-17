<?php

namespace Modules\Core\Base;


class FrontController extends BaseController
{
    /**
     * 初始化
     */
    public function __init()
    {
        parent::__init();

        // 默认为default主题
        $this->theme  = config('site.theme', 'default');
        $this->locale = config('site.locale', null);
    }

}
