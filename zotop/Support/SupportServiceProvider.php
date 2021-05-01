<?php

namespace Zotop\Support;

use Illuminate\Support\ServiceProvider;
use Zotop\Support\ImageFilters\Fit;
use Zotop\Support\ImageFilters\Resize;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bladeExtend();
        $this->imageFilter();
    }

    /**
     * 模板扩展
     *
     * @author Chen Lei
     * @date 2020-11-25
     */
    private function bladeExtend()
    {

    }

    /**
     * 图片滤器
     *
     * @author Chen Lei
     * @date 2020-11-25
     */
    private function imageFilter()
    {
        // 定义图片滤器
        ImageFilter::set('fit', Fit::class);
        ImageFilter::set('resize', Resize::class);
    }

}
