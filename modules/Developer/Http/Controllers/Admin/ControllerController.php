<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\Core\Base\AdminController;
use Module;
use Artisan;
use File;
use Filter;

class ControllerController extends AdminController
{
    /**
     * 控制器类型
     * 
     * @param  string $type [admin，front]
     * @return mixed
     */
    private function types($type='', $key='')
    {
        $types = Filter::fire('developer.controller.types',[
            'admin' => [
                'name'    =>trans('developer::controller.admin'),
                'path'    =>'Http/Controllers/Admin',
                'artisan' =>'module:make-admin-controller',
                'styles'   => [
                    'resource' => trans('developer::controller.style.resource'),
                    'simple'   => trans('developer::controller.style.simple'),
                ],
                'middleware' => 'allow:{allow}'
            ],
            'front' => [
                'name'    => trans('developer::controller.front'),
                'path'    => 'Http/Controllers',
                'artisan' => 'module:make-front-controller',
                'styles'   => [
                    'simple'   => trans('developer::controller.style.simple'),
                    'resource' => trans('developer::controller.style.resource'),
                ],
                'middleware' => ''                
            ],
        ]);

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
     * 获取控制器完整名称，加上Controller后缀
     * 
     * @param  string $controller 控制器名称
     * @return string
     */
    private function fullname($controller)
    {
        // 如果不包含Controller后缀
        if (strtolower($controller) == 'controller' || ends_with(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return Str::studly($controller);
    }

    /**
     * 获取控制器真实名称，去掉Controller后缀
     * 
     * @param  string $controller 控制器名称
     * @return string
     */
    private function realname($controller)
    {
        // 如果包含Controller后缀
        if (strlen($controller) > 10 && ends_with(strtolower($controller), 'controller') == true) {
            $controller = substr($controller, 0, -10);
        }

        return strtolower($controller);
    }    

    /**
     * 根据 模块、控制器类型和控制器名称获取控制器的文件路径
     * 
     * @param  string $module     模块名称
     * @param  string $type       控制器类型
     * @param  string $controller 控制名称，可以不包含Controller后缀
     * @return string
     */
    private function fullpath($module, $type, $controller)
    {
        return module_path($module).'/'.$this->types($type,'path').'/'.$this->fullname($controller).'.php';
    }

    /**
     * 根据 模块、控制器类型和控制器名称获取控制器的完整类名（含命名空间）
     * 
     * @param  string $module     模块名称
     * @param  string $type       控制器类型
     * @param  string $controller 控制名称，可以不包含Controller后缀
     * @return string
     */    
    private function fullclass($module, $type, $controller)
    {
        $module     = Str::studly($module);       
        $controller = $this->fullname($controller);        
        $path       = $this->types($type,'path');
        $path       = str_replace('/', '\\', $path);

        return "Modules\\{$module}\\{$path}\\{$controller}";
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
        
        $this->name    = $module;
        $this->type    = $type;
        $this->module  = module($module);
        $this->types   = $this->types();
        $this->path    = $this->types($type,'path');
        $this->path    = $this->module->getExtraPath($this->path);
        $this->files   = File::files($this->path);
        $this->artisan = $this->types($type,'artisan');
        $this->styles  = $this->types($type,'styles');

        return $this->view();
    }


    /**
     * 创建控制器
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @param  string $module 模型名称
     * @return mixed
     */
    public function create(Request $request, $module, $type)
    {
        $this->module = $module;
        $this->type   = $type;

        // 表单提交时
        if ($request->isMethod('POST')) {
            
            $name  = $request->input('name');
            $style = $request->input('style');

            // 判断是否已经存在
            $path = $this->fullpath($module, $type, $name);

            if (File::exists($path)) {
                return $this->error(trans('core::master.existed'));
            }

            $artisan = $this->types($type, 'artisan');

            Artisan::call($artisan, [
                'module'     => $module,
                'controller' => $name,
                '--style'    => $style,
                '--force'    => false,
            ]);

            return $this->success(trans('core::master.saved'),route('developer.controller.index',[$module,$type]));
        }


        $this->title      = trans('core::master.create');

        $this->controller = [];
        $this->styles     = $this->types($type,'styles');

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

        $class = $this->fullclass($module, $type, $controller);

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

            $router[$m->name]['uri']        = implode('/', $uri);
            $router[$m->name]['action']     = $this->fullname($controller).'@'.$m->name;
            $router[$m->name]['name']       = strtolower($module).'.'.$this->realname($controller).'.'.$m->name;
            $router[$m->name]['verb']       = $this->verbs($m->name);
            $router[$m->name]['middleware'] = str_replace('{allow}',$router[$m->name]['name'],$this->types($type,'middleware'));            
         }

        $this->type   = $type;
        $this->prefix = $this->realname($controller);
        $this->router = $router;

        return $this->view();
    }
    
}
