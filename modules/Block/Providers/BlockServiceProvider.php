<?php

namespace Modules\Block\Providers;

use Illuminate\Support\ServiceProvider;
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

    public function baldeBlockTag()
    {
        // 解析{block ……}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%sblock(\s+[^}]+?)\s*%s(\r?\n)?/s', '{', '}');

            $callback = function ($matches)  {

                $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

                $attrs = Blade::convertAttrs($matches[2]);

                return $matches[1] ? substr($matches[0], 1) : "<?php echo block_tag(".$attrs."); ?>{$whitespace}";
            };

            return preg_replace_callback($pattern, $callback, $value);
        });        
    }
}
