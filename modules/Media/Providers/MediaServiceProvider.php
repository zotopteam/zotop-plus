<?php

namespace Modules\Media\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Media\View\Components\MediaBreadcrumb;
use Modules\Media\View\Components\MediaList;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 模板扩展
        Blade::component(MediaBreadcrumb::class, 'media-breadcrumb');
        Blade::component(MediaList::class, 'media-list');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
