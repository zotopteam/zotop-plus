<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;
use Module;
use Theme;

class ThemeMiddleware
{
    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */       
    protected $app;

    /**
     * view实例
     * 
     * @var object
     */      
    protected $view;

    /**
     * 初始化
     */
    public function __construct() {
        $this->app  = app();
        $this->view = $this->app['view'];
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
        $this->registerThemeViews();

        // 注册模块命名空间view
        $this->registerNamespaces();

        return $next($request);
    }

    /**
     * 注册主题模板和模块的views，实现在主题和模块中寻址
     * 
     * @return void
     */
    protected function registerThemeViews()
    {
        $theme = $this->app['current.theme'];

        // 注册主题模板，实现view在主题中寻址
        $this->app->singleton('current.theme', function() use ($theme) {
            return Theme::active($theme);
        });  
        
        // 在主题对应模块下的目录中寻址
        $this->view->addLocation($this->app['current.theme']->path.'/views/'.strtolower($this->app['current.module']));

        // 注册当前模块的views，实现view在模块中寻址
        $this->view->addLocation(Module::getModulePath($this->app['current.module']) . '/Resources/views/'.strtolower($this->app['current.type']));
         
    }

    /**
     * 注册资源命名空间，按照命名空间寻址
     * 
     * @return void
     */
    protected function registerNamespaces()
    {
        foreach (Module::getOrdered() as $module) {

            // 模型名称和路径
            $name = $module->getLowerName();
            $path = $module->getPath();

            // 注册命名空间
            $this->view->addNamespace($name, [$this->app['current.theme']->path.'/views/'.$name, $path . '/Resources/views/'.$this->app['current.type']]);
        }
    }    

}
