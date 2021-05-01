<?php

namespace Zotop\Hook;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class HookServiceProvider
 *
 * @package Zotop\Hook
 */
class HookServiceProvider extends ServiceProvider
{
    /**
     * 命令行
     *
     * @var array
     */
    protected $commands = [
        Commands\ListFilterCommand::class,
        Commands\ListActionCommand::class,
    ];

    /**
     * 别名
     *
     * @var array
     */
    protected $aliases = [
        'Filter' => Facades\Filter::class,
        'Action' => Facades\Action::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHook();
        $this->registerCommands();
        $this->registerAlias();
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
    }

    /**
     * 注册Hook的action和filter
     *
     * @author Chen Lei
     * @date 2021-03-18
     */
    protected function registerHook()
    {
        $this->app->singleton('hook.action', function ($app) {
            return new Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new Filter($app);
        });
    }

    /**
     * 注册命令行
     *
     * @author Chen Lei
     * @date 2021-03-18
     */
    protected function registerCommands()
    {
        $this->commands($this->commands);
    }

    /**
     * 注册别名
     *
     * @author Chen Lei
     * @date 2021-03-18
     */
    public function registerAlias()
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }
}
