<?php

namespace Modules\Site\Providers;

use Zotop\Modules\Routing\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 控制器命名空间
     *
     * @var string
     */
    protected $namespace = 'Modules\Site\Http\Controllers';

    /**
     * 模块名称
     *
     * @var string
     */
    protected $module = 'site';
}
