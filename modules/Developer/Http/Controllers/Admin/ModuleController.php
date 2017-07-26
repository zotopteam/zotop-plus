<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Developer\Http\Requests\ModuleRequest;
use Module;
use Artisan;
use File;
use Filter;

class ModuleController extends AdminController
{  
    /**
     * 获取module信息
     * 
     * @param  string $moduleName [description]
     * @return mixed 
     */
    private function modules($moduleName='')
    {
        // 获取全部模块
        $modules = Module::all();

        // 默认安装顺序排序
        $direction = 'asc';

        // 模块排序
        uasort($modules, function ($a, $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });   


        foreach ($modules as $name=>$module) {
            
            $namespace = strtolower($name);

            // module img
            if ( empty($module->icon) ) {
                $module->icon = $module->getExtraPath('Assets/module.png');
                $module->icon = preview($module->icon);
            }

            // 加载未启用模块语言包
            if ( !$module->active ) {
                App('translator')->addNamespace($namespace, $module->getPath() . '/Resources/lang');
            }

            // 标题和描述语言化
            $module->title       = trans($module->title);
            $module->description = trans($module->description);                   
        }

        return $moduleName ? $modules[$moduleName] : $modules;        
    }

    /**
     * 控制器类型
     * 
     * @param  string $type [admin，front]
     * @return mixed
     */
    private function types($type='', $key='')
    {
        $types = Filter::fire('developer.controller.type',[
            'admin' => ['name'=>trans('developer::module.controller.admin'),'path'=>'Http/Controllers/Admin','artisan'=>'module:make-admin-controller'],
            'front' => ['name'=>trans('developer::module.controller.front'),'path'=>'Http/Controllers','artisan'=>'module:make-front-controller'],
        ]);

        if ( empty($key) ) {
            return $type ? $types[$type] : $types;
        }

        return $types[$type][$key];
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('developer::module.develop');
        $this->modules = $this->modules();

        return $this->view();
    }

    /**
     * 展示
     * 
     * @return Response
     */
    public function show(Request $request, $name)
    {
        $this->name   = $name;
        $this->module = $this->modules($name);
        $this->json   = $this->module->json();
        $this->path   = $this->module->getPath();
                
        $this->title  = trans('developer::module.show');

        return $this->view();        
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->plains = [
            false => trans('developer::module.plain.false'),
            true  => trans('developer::module.plain.true'),
        ];

        $this->module = [
            'plain' => false
        ];

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(ModuleRequest $request)
    {
        $name = $request->input('name');
        $plain = $request->input('plain');

        Artisan::call("module:create", [
            'name'    => $name,
            '--plain' => $plain ? true : false,
            '--force' => false,
        ]);

        return $this->success(trans('core::master.created'),route('developer.module.index'));
    }

    /**
     * 编辑单个JSON字段
     *
     * @return Response
     */
    public function update(Request $request, $name, $field)
    {
        $newvalue = $request->input('newvalue');

        if ( $field == 'order' ) {
            $this->validate($request, ['newvalue' => 'required|numeric|min:1'],[],['newvalue'=>'']);
        } elseif ($field == 'version') {
            $this->validate($request, ['newvalue' => ['required','regex:/^[0-9]+.[0-9]+.[0-9]+$/']],[],['newvalue'=>'']);
        } else {
            $this->validate($request, ['newvalue' => 'required'],[],['newvalue'=>'']);
        }

        // Find Module
        $module = Module::find($name);

        // Update module.json
        $module->json()->set($field, $newvalue)->save();

        return $this->success(trans('core::master.saved'),route('developer.module.show',[$name]));
    }

    /**
     * 控制器
     * 
     * @param  Request $request
     * @param  string $name 模型名称
     * @param  string $name 模型名称
     * @return mixed
     */
    public function controller(Request $request, $name, $type)
    {
        $this->title  = trans('developer::module.controller');

        $this->name   = $name;
        $this->type   = $type;
        $this->module = $this->modules($name);
        $this->types  = $this->types();
        $this->path   = $this->types($type,'path');
        $this->path   = $this->module->getExtraPath($this->path);
        $this->files  = File::files($this->path);

        return $this->view();
    }

    /**
     * 创建控制器
     * 
     * @param  Request $request
     * @param  string $name 模型名称
     * @return mixed
     */
    public function makeController(Request $request, $name, $type)
    {
        $this->name   = $name;
        $this->type   = $type;

        // 表单提交时
        if ( $request->isMethod('POST') ) {
            
            $controller_name = $request->input('controller_name');
            $controller_plain = $request->input('controller_plain');

            $artisan = $this->types($type, 'artisan');

            Artisan::call($artisan, [
                'module'     => $name,
                'controller' => $controller_name,
                '--plain'    => $controller_plain ? true : false,
                '--force'    => false,
            ]);


            return $this->success(trans('core::master.saved'),route('developer.module.controller',[$name,$type]));
        }


        $this->title      = trans('developer::module.controller');
        $this->plains     = [
            false => trans('developer::module.controller_plain.false'),
            true  => trans('developer::module.controller_plain.true'),
        ];
        $this->controller = ['controller_plain'=>false];

        return $this->view();
    }

}
