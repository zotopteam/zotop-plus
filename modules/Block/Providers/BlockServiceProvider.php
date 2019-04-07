<?php

namespace Modules\Block\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Block\Models\Block;
use Blade;

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
        $this->app['events']->listen('modules.block.uninstalling', function($module) {
            abort_if(Block::count() > 0, 403, trans('block::block.uninstall.forbidden'));
        });

        $this->baldeBlockTag();
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

    private function baldeBlockTag()
    {
        // 解析{block ……}
        Blade::tag('block', function($attrs) {

            $id   = array_get($attrs, 'id');
            $slug = array_get($attrs, 'slug');
            $view = array_get($attrs, 'view');

            if ($id || $slug) {

                $block = $id ? Block::Where('id', $id)->first() : Block::where('slug', $slug)->first();

                // 如果block存在，解析并返回
                if ($block) {
                    return app('view')->make($view ?: $block->view)->with('attrs',$attrs)->with('block',$block)->render();
                }

                return trans('block::block.notexist', [$id ?: $slug]);
            }
            
            return null;
        });
    }
}
