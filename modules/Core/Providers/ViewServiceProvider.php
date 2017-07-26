<?php

namespace Modules\Core\Providers;

use Illuminate\View\ViewServiceProvider as LaravelViewServiceProvider;
use Modules\Core\Base\BladeCompiler;

/**
 * Class MacroServiceProvider
 * @package App\Providers
 */
class ViewServiceProvider extends LaravelViewServiceProvider
{
    /**
     * 扩展blade引擎
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $this->app->singleton('blade.compiler', function () {
            return new BladeCompiler(
                $this->app['files'], $this->app['config']['view.compiled']
            );
        });

        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler']);
        });
    }
}