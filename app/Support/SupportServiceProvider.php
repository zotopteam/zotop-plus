<?php

namespace App\Support;

use App\Support\Form;
use App\Support\Html;
use App\Support\Action;
use App\Support\Filter;
use App\Support\ImageFilter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('html', function ($app) {
            return new Html($app);
        });

        $this->app->singleton('form', function ($app) {
            return new Form($app);
        });

        $this->app->singleton('hook.action', function ($app) {
            return new Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new Filter($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Adds a directive in Blade for actions
         */
        Blade::directive('action', function ($expression) {
            return "<?php Action::fire($expression); ?>";
        });

        /**
         * Adds a directive in Blade for filters
         */
        Blade::directive('filter', function ($expression) {
            return "<?php echo Filter::fire($expression); ?>";
        });

        // 解析{form ……}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%sform(\s+[^}]+?)\s*%s/s', '{', '}');

            return preg_replace_callback($pattern, function ($matches) {
                $attrs = Blade::convertAttrs($matches[2]);
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::open(" . $attrs . "); ?>";
            }, $value);
        });

        // 解析{/form}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%s(\/form)%s/s', '{', '}');

            return preg_replace_callback($pattern, function ($matches) {
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::close(); ?>";
            }, $value);
        });

        // 解析{field ……}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%sfield(\s+[^}]+?)\s*%s/s', '{', '}');

            return preg_replace_callback($pattern, function ($matches) {
                $attrs = Blade::convertAttrs($matches[2]);
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::field(" . $attrs . "); ?>";
            }, $value);
        });

        // 定义滤器
        ImageFilter::set('fit', \App\Support\ImageFilters\Fit::class);
        ImageFilter::set('resize', \App\Support\ImageFilters\Resize::class);
    }
}
