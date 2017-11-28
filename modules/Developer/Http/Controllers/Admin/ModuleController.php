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
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title   = trans('developer::module.title');
        $this->modules = module();

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
        $this->module = module($name);
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
        $name  = $request->input('name');
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
}
