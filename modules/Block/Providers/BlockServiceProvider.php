<?php

namespace Modules\Block\Providers;

use Illuminate\Support\Facades\Blade;
use App\Modules\Support\ServiceProvider;
use Modules\Block\Models\Block;
use Modules\Block\View\Components\BlockComponent;

class BlockServiceProvider extends ServiceProvider
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
        // 监听卸载
        $this->app['events']->listen('modules.block.uninstalling', function ($module) {
            abort_if(Block::count() > 0, 403, trans('block::block.uninstall.forbidden'));
        });

        $this->BladeExtend();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
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

    /**
     * 模板扩展
     *
     * @return void
     */
    private function BladeExtend()
    {
        Blade::component(BlockComponent::class, 'block');
    }
}
