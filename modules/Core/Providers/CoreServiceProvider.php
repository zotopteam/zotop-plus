<?php

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Traits\PublishConfig;
use Nwidart\Modules\Module;
use Modules\Core\Models\Config;
use Blade;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * 中间件
     *
     * @var array
     */
    protected $middlewares = [
        'module' => 'ModuleMiddleware',
        'admin'  => 'AdminMiddleware',
        'front'  => 'FrontMiddleware',
        'allow'  => 'AllowMiddleware',
    ];    

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware();
        $this->registerCurrent();
        $this->registerModules();
        $this->registerThemes();
        $this->setLocale();
        $this->eventsListen();
        $this->bladeExtend();
        $this->paginatorDefault();       
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerSingleton();
    }

    /**
     * 注册中间件
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware()
    {
        foreach ($this->middlewares as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, "Modules\\Core\\Http\\Middleware\\{$middleware}");
        }
    }

    /**
     * 注册模块文件
     * @return null
     */
    public function registerModules()
    {
        foreach ($this->app['modules']->getOrdered() as $module) {

            $moduleName = $module->getLowerName();

            // 已安装时加载自定义配置
            if ($this->app['installed'] == true && $moduleConfig = Config::get($moduleName)) {
               $this->app['config']->set($moduleName, $moduleConfig);
            }

            // 未安装的时加载模块根目录下的配置
            if ($this->app['installed'] == false && $this->app['files']->isFile($configFile = $module->getPath().'/config.php')) {            
                $this->mergeConfigFrom($configFile, $moduleName);
            }

            // 注册语言包，如果已经publish并且模块语音文件夹存在
            if (is_dir($moduleLang = base_path("resources/lang/{$moduleName}"))) {
                $this->loadTranslationsFrom($moduleLang, $moduleName);
            } else {
                $this->loadTranslationsFrom($module->getPath() . '/Resources/lang', $moduleName);
            }

            // 非产品环境下注册Factories
            if (! $this->app->environment('production')) {
                $this->app->make(Factory::class)->load($module->getPath() . '/Database/Factories');
            }
        }        
    }

    /**
     * 注册主题
     *
     * @return void
     */
    protected function registerThemes()
    {
        // 获取主题目录并注册全部主题        
        $path = $this->app['config']->get('themes.paths.themes', base_path('/themes'));

        $dirs = $this->app['files']->directories($path);

        foreach ($dirs as $dir) {
            $this->app['theme']->registerPath($dir);
        }

        // 启用当前主题
        $this->app['theme']->active();
    }

    /**
     * 设置当前语言
     */
    public function setLocale()
    {
        $locale = $this->app['current.locale'];

        // 当前语言设置
        if ($locale != $this->app->getLocale()) {
            $this->app->setLocale($locale);        
        }

        // Carbon 语言转换
        $locale = Arr::get($this->app['hook.filter']->fire('carbon.locale.transform', [
            'zh-Hans' => 'zh',
            'zh-Hant' => 'zh_TW'
        ]), $locale, $locale);
        
        Carbon::setLocale($locale);
    }

    /**
     * 事件监听
     * @return null
     */
    public function eventsListen()
    {
        // 禁止禁用和卸载核心模块
        $this->app['events']->listen('modules.*.*', function($event, $modules) {
            if (ends_with($event, 'uninstalling') || ends_with($event, 'disabling')) {
                foreach ($modules as $module) {
                    if (in_array($module->getLowerName(), config('modules.cores', ['core']))) {
                        abort(403,trans('core::module.core_operate_forbidden'));
                    }
                }
            }
        });        
    }

    /**
     * 注册命令行
     * 
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Modules\Core\Console\CreateCommand::class,
            \Modules\Core\Console\CreateThemeCommand::class,
            \Modules\Core\Console\MakeTraitCommand::class,
            \Modules\Core\Console\AdminControllerCommand::class,
            \Modules\Core\Console\FrontControllerCommand::class,
            \Modules\Core\Console\ApiControllerCommand::class,
            \Modules\Core\Console\RebootCommand::class,
            \Modules\Core\Console\PublishThemeCommand::class,
        ]);
    }

    /**
     * 注册实例
     * @return null
     */
    public function registerSingleton()
    {
        $this->app->singleton('format', function ($app) {
            return new \Modules\Core\Support\Format($app);
        });

        $this->app->singleton('hook.action', function ($app) {
            return new \Modules\Core\Support\Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new \Modules\Core\Support\Filter($app);
        });

        $this->app->singleton('theme', function($app){
            return new \Modules\Core\Support\Theme($app);
        });

        // 覆盖系统默认的BladeCompiler
        $this->app->singleton('blade.compiler', function () {
            return new \Modules\Core\Base\BladeCompiler(
                $this->app['files'], $this->app['config']['view.compiled']
            );
        });        
    }

    /**
     * 注册当前类型[后台、前台、api]，当前语言和当前主题
     * @return null
     */
    public function registerCurrent()
    {
        // 注册当前类型，根据uri的第一个段来判断是前台、后台或者api
        $this->app->singleton('current.type', function($app) {
            $type  = 'front';
            $begin = strtolower($app['request']->segment(1));
            if ($begin == strtolower($app['config']->get('app.admin_prefix', 'admin'))) {
                $type = 'admin';
            }
            if ($begin == 'api') {
                $type = 'api';
            }
            return $app['hook.filter']->fire('current.type', $type);
        });

        // 注册当前语言
        $this->app->singleton('current.locale', function($app) {
            return $app['hook.filter']->fire('current.locale', $app->getLocale());
        });

        // 注册当前主题，默认为：core.theme.admin，core.theme.front，core.theme.api
        $this->app->singleton('current.theme', function($app) {
            $theme = $app['config']->get('core.theme.'.$app['current.type'], $app['current.type']);
            return $app['hook.filter']->fire('current.theme', $theme);
        });
    }

    // 模板扩展
    public function bladeExtend()
    {
        // 模板中的权限指令
        Blade::if('allow', function ($permission) {
            return allow($permission);
        });
        
        /**
         * Adds a directive in Blade for actions
         */
        Blade::directive('size', function($expression) {
            return "<?php echo Format::size($expression); ?>";
        });

        /**
         * Adds a directive in Blade for actions
         */
        Blade::directive('action', function($expression) {
            return "<?php Action::fire$expression; ?>";
        });

        /**
         * Adds a directive in Blade for filters
         */
        Blade::directive('filter', function($expression) {
            return "<?php echo Filter::fire$expression; ?>";
        });

        // 只执行一次 @once('……') @endonce
        Blade::directive('once', function ($expression) {
            $expression = strtoupper($expression);
            return "<?php if (! isset(\$__env->once[{$expression}])) : \$__env->once[{$expression}] = true; ?>";
        }); 

        Blade::directive('endonce', function () {
            return "<?php endif; ?>";
        });

        // 只加载一次js文件 @loadjs('……')
        Blade::directive('loadjs', function ($expression) {
            return "<?php \$loadjs = {$expression}; if (! isset(\$__env->loadjs[\$loadjs])) : \$__env->loadjs[\$loadjs] = true;?>\r\n<script src=\"<?php echo \$loadjs; ?>\"></script>\r\n<?php endif; ?>";
        });

        // 只加载一次css文件 @loadcss('……')
        Blade::directive('loadcss', function ($expression) {
            return "<?php \$loadcss = {$expression}; if (! isset(\$__env->loadcss[\$loadcss])) : \$__env->loadcss[\$loadcss] = true;?>\r\n<link rel=\"stylesheet\" href=\"<?php echo \$loadcss; ?>\" rel=\"stylesheet\">\r\n<?php endif; ?>";
        });        
    }

    /**
     * 设置默认分页代码
     * @return null
     */
    public function paginatorDefault()
    {
        \Illuminate\Pagination\Paginator::defaultView('core::pagination.default');
        \Illuminate\Pagination\Paginator::defaultSimpleView('core::pagination.simple');     
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
