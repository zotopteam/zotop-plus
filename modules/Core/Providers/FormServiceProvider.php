<?php

namespace Modules\Core\Providers;

use Modules\Core\Base\FormBuilder;
use Collective\Html\HtmlServiceProvider;
use Blade;

/**
 * Class MacroServiceProvider
 * @package App\Providers
 */
class FormServiceProvider extends HtmlServiceProvider
{

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bladeFormTag();
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token());
            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * 解析form标签
     * 
     * @return [type] [description]
     */
    public function bladeFormTag()
    {
        // 解析{form ……}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%sform(\s+[^}]+?)\s*%s(\r?\n)?/s', '{', '}');

            $callback = function ($matches)  {

                $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

                $attrs = Blade::convertAttrs($matches[2]);

                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::open(".$attrs."); ?>{$whitespace}";
            };

            return preg_replace_callback($pattern, $callback, $value);
        });

        // 解析{field ……}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%sfield(\s+[^}]+?)\s*%s(\r?\n)?/s', '{', '}');

            $callback = function ($matches)  {

                $whitespace = empty($matches[3]) ? '' : $matches[3].$matches[3];

                $attrs = Blade::convertAttrs($matches[2]);

                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::field(".$attrs."); ?>{$whitespace}";
            };

            return preg_replace_callback($pattern, $callback, $value);
        });             

        // 解析{/form}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%s(\/form)%s/s', '{', '}');

            return preg_replace($pattern, "<?php echo Form::close(); ?>\n", $value);
        });           
    }
}
