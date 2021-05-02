<?php

namespace Zotop\Image;

use Illuminate\Support\ServiceProvider;
use Zotop\Image\Filters\Fit;
use Zotop\Image\Filters\Resize;

class ImageServiceProvider extends ServiceProvider
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
        // 定义图片滤器
        Filter::set('fit', Fit::class);
        Filter::set('resize', Resize::class);
    }
}
