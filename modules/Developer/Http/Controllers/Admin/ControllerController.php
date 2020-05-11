<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Support\Facades\Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Modules\Routing\AdminController;
use App\Modules\Exceptions\FileExistedException;

class ControllerController extends AdminController
{
    /**
     * 控制器类型
     * 
     * @param  string $type [admin，front]
     * @return mixed
     */
    private function types($type=null, $key=null)
    {
        $types = [];

        foreach (config('modules.types') as $k => $v) {
            $types[$k]['title'] = trans("developer::controller.{$k}");

            if ($dir = Arr::get($v, 'dirs.controller')) {
                $types[$k]['path'] = config('modules.paths.dirs.controller').'/'.$dir;
            } else {
                $types[$k]['path'] = config('modules.paths.dirs.controller');
            }
        }

        if ( empty($key) ) {
            return $type ? $types[$type] : $types;
        }

        return $types[$type][$key];
    }

    /**
     * 获取路由方法，从方法名称转换
     * 
     * @param  string $action 方法名称
     * @return string
     */
    private function verbs($action) {
        
        $verbs = Filter::fire('developer.controller.router.verbs',[
            'index'   => 'get',
            'create'  => 'get',
            'store'   => 'post',
            'show'    => 'get',
            'edit'    => 'get',
            'update'  => 'put',
            'destroy' => 'delete',
        ]);

        $action = strtolower($action);

        return isset($verbs[$action]) ? $verbs[$action] : 'any';
    }
  


    /**
     * 根据 模块、控制器类型和控制器名称获取控制器的完整类名（含命名空间）
     * 
     * @param  string $module     模块名称
     * @param  string $type       控制器类型
     * @param  string $controller 控制名称，可以不包含Controller后缀
     * @return string
     */    
    private function getFullName($module, $type, $controller)
    {
        $namespace  = config('modules.namespace');
        $module     = Module::findOrFail($module)->getStudlyName();      
        //$controller = Str::finish($controller, 'Controller');        
        $path       = str_replace('/', '\\', $this->types($type, 'path'));
        return "{$namespace}\\{$module}\\{$path}\\{$controller}";
    }

    /**
     * 获取控制器的真实类名称
     * @param  string $controller
     * @return string
     */
    private function getRealName($controller)
    {
        return $controller;
    }

    /**
     * 获取控制器的基本名称
     * @param  string $controller
     * @return string
     */
    private function getBaseName($controller)
    {
        return Str::replaceLast('controller', '', strtolower($controller));
    }

    /**
     * 控制器
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @param  string $module 模型名称
     * @return mixed
     */
    public function index(Request $request, $module, $type)
    {
        $this->title   = trans('developer::controller.title');
        
        $this->type    = $type;
        $this->module  = Module::findOrFail($module);
        $this->types   = $this->types();
        $this->path    = $this->module->getPath($this->types($type, 'path'));
        $this->files   = File::isDirectory($this->path) ? File::files($this->path) : [];


        return $this->view();
    }


    /**
     * 创建控制器
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @param  string $type 控制器类型
     * @return mixed
     */
    public function create(Request $request, $module, $type)
    {
        // 表单提交时
        if ($request->isMethod('POST')) {
            
            $name  = $request->input('name');
            $model = $request->input('model');

            try {
                Artisan::call('module:make-controller', [
                    'module'  => $module,
                    'name'    => $name,
                    '--type'  => $type,
                    '--model' => $model,
                ]);
                return $this->success(trans('master.saved'),route('developer.controller.index',[$module, $type]));
            } catch (FileExistedException $e) {
                return $this->error(trans('master.existed', [$name]));
            }
        }


        $this->title  = trans('master.create');
        $this->module = Module::findOrFail($module);
        $this->type   = $type;

        return $this->view();
    }

    /**
     * 反射控制器并生成路由示例
     * 
     * @param  string $module     模块名称
     * @param  string $type       控制器类型
     * @param  string $controller 控制名称，可以不包含Controller后缀
     * @return string
     */
    public function route($module, $type, $controller)
    {
        $class = $this->getFullName($module, $type, $controller);

        $router   = [];
        
        $reflector = new \ReflectionClass($class);

        foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $m) {
            
            //  过滤掉从父类继承的方法
            if ($m->class != $class) continue;

            // 基础参数等于当前的方法名称
            $uri = [$m->name];

            // 反射参数
            $method = $reflector->getmethod($m->name);
            $params = $method->getParameters();

            foreach ($params as $p) {

                // 如果存在参数类型
                if ($p->hasType()) {
                    
                    // 如果不是PHP内建参数类型，比如 Request，直接跳过
                    if ($p->getType()->isBuiltin() == false) continue;

                    // 数组参数，没法通过url传值，直接跳过
                    if ($p->getType() == 'array') continue;

                }

                // 参数是否可选
                if ($p->isOptional()) {
                    $uri[] = '{'.$p->name.'?}';
                } else {
                    $uri[] = '{'.$p->name.'}';
                }                
            }

            $router[$m->name]['module']     = strtolower($module);
            $router[$m->name]['controller'] = $this->getBaseName($controller);
            $router[$m->name]['method']     = $m->name;
            $router[$m->name]['uri']        = implode('/', $uri);
            $router[$m->name]['action']     = $this->getRealName($controller).'@'.$m->name;
            $router[$m->name]['name']       = $router[$m->name]['module'].'.'.$router[$m->name]['controller'].'.'.$m->name;
            $router[$m->name]['verb']       = $this->verbs($m->name);
         }

        $this->type   = $type;
        $this->prefix = $this->getBaseName($controller);
        $this->router = $router;

        return $this->view();
    }
    
}
