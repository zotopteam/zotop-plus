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
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%sblock(\s+[^}]+?)\s*%s/s', '{', '}');

            $callback = function ($matches)  {

                // 如果有@符号，@{block……} ，直接去掉@符号返回标签
                if ($matches[1]) {
                    return substr($matches[0], 1);
                }

                // 返回解析
                return '<?php echo block_tag('.Blade::convertAttrs($matches[2]).'); ?>';
            };

            return preg_replace_callback($pattern, $callback, $value);
        });        
    }
}
