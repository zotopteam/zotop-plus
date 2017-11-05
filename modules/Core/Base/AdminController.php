<?php

namespace Modules\Core\Base;


class AdminController extends BaseController
{
    /**
     * 初始化
     */
    public function __init()
    {
        parent::__init();

        // 默认为admin主题
        $this->theme = config('core.theme','admin');
    }
}
