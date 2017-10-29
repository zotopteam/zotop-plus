<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Route;

class InstallController extends Controller
{
    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */    
    protected $app;

    /**
     * 本地语言
     * 
     * @var string
     */    
    protected $locale;

    /**
     * view 实例
     * 
     * @var object
     */
    protected $view;

    /**
     * view 数据
     * 
     * @var array
     */
    protected $viewData = [];

    /**
     * __construct
     */
    public function __construct()
    {
        // app实例
        $this->app = app();

        if ($this->app->runningInConsole() === true) {
            return;
        }

        // view实例
        $this->view    = $this->app->make('view');      
        
        // wizard
        $this->wizard  = ['welcome','check','config','modules','installing','finished'];
        
        // 获取当前动作指针
        $this->current = array_search(Route::getCurrentRoute()->getActionMethod(), $this->wizard);

        // 获取前一个动作
        $this->prev    = ($this->current > 0) ? $this->wizard[$this->current - 1] : null;
        
        // 获取下一个动作
        $this->next    = ($this->current < (count($this->wizard) -1)) ? $this->wizard[$this->current+1] : null;

        // 获取当前动作
        $this->current = $this->wizard[$this->current];

    }

    /**
     * 传入参数, 支持链式
     * 
     * @param  string|array $key 参数名
     * @param  mixed $value 参数值
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->viewData = array_merge($this->viewData, $key);
        } else {
            $this->viewData[$key] = $value;
        }

        return $this;
    }

    /**
     * 模板变量赋值，魔术方法
     *
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return void
     */
    public function __set($key, $value)
    {
        $this->viewData[$key] = $value;
    }

    /**
     * 取得模板显示变量的值
     * 
     * @access protected
     * @param string $name 模板显示变量
     * @return mixed
     */
    public function __get($key)
    {
        return $this->viewData[$key];
    }    

    /**
     * 显示View
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = [])
    {
        // 默认模板
        $view = empty($view) ? "install.{$this->current}" : $view;

        // 转换模板数据
        $data = ($data instanceof Arrayable) ? $data->toArray() : $data;

        // 合并模板数据
        $data = array_merge($this->viewData, $data);

        // 生成 view
        return $this->view->make($view, $data, $mergeData);
    }    

    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {               
        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function check()
    {
        $check = true;
        $error = [];

        // php version must > 7.0.0
        if (version_compare(PHP_VERSION, config('install.php_version','7.0.0'), '<')) {
            $check = false;
            $error['php_version'] = [config('install.php_version','7.0.0'), PHP_VERSION];
        }

        // php extensions
        foreach(config('install.php_extensions', []) as $extension) {

            if(!extension_loaded($extension)) {
                $check = false;
                $error['php_extensions'][$extension] = false;
            }
        }
            // if function doesn't exist we can't check apache modules
        if(function_exists('apache_get_modules') && $apache_get_modules = apache_get_modules()) {

            foreach (config('install.apache', []) as $requirement) {

                if(!in_array($requirement, apache_get_modules())) {
                    $check = false;
                    $error['apache'][$requirement] = false;
                }
            }
        }

        foreach(config('install.permissions', []) as $path => $permission) {
            
            $realpath = base_path($path);

            if ($this->app['files']->exists($realpath)) {         

                $server_permission = substr(sprintf('%o', fileperms($realpath)), -4);

                if($server_permission < $permission) {
                    $check = false;
                    $error['permissions'][$path] = [$permission, $server_permission];
                }
            } else {
                $check = false;
                $error['permissions'][$path] = [$permission, null];                
            }
        }

        $this->check = $check;
        $this->error = $error;

        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function config()
    {
        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function modules()
    {
        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function installing()
    {
        return $this->view();
    }

    /**
     * Display the installer check page.
     *
     * @return \Illuminate\Http\Response
     */
    public function finished()
    {
        return $this->view();
    }                               
}
