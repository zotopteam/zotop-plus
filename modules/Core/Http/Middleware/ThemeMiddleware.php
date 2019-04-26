<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use Route;
use Module;
use Theme;

class ThemeMiddleware
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 注册主题和模块view
        $this->registerViews();

        // 注册语言
        $this->registerLanguages();

        // 注册启动文件
        $this->registerFiles();

        return $next($request);
    }

    /**
     * 注册主题模板和模块的views，实现在主题和模块中寻址
     * 
     * @return void
     */
    protected function registerViews()
    {
        // 主题寻址
        $this->app['config']->set('view.paths', [$this->app['theme']->path().'/views', resource_path('views')]);

        // 在主题对应模块下的目录中寻址
        $this->app['view']->addLocation($this->app['theme']->path().'/views/'.strtolower($this->app['current.module']));

        // 注册当前模块的views，实现view在模块中寻址
        $this->app['view']->addLocation(Module::getModulePath($this->app['current.module']) . '/Resources/views/'.strtolower($this->app['current.type']));

        //注册模块名称为命名空间，按照命名空间寻址
        foreach (Module::getOrdered() as $module) {
            $this->app['view']->addNamespace($module->getLowerName(), [
                $this->app['theme']->path().'/views/'.$module->getLowerName(),
                $module->getPath() . '/Resources/views/'.strtolower($this->app['current.type'])
            ]);
        }        
    }

    /**
     * 注册语言包
     * 
     * @return void
     */    
    protected function registerLanguages()
    {
        $this->app['translator']->addJsonPath($this->app['theme']->path().'/lang');

        $this->app['translator']->addNamespace('theme', $this->app['theme']->path().'/lang');
    } 

    /**
     * 注册主题文件
     * 
     * @return void
     */
    protected function registerFiles()
    {
        $file = $this->app['theme']->path().'/theme.php';

        if ($this->app['files']->exists($file)) {
            require $file;
        }
    }
}
