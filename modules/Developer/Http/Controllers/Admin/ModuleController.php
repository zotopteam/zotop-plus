<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Hook\Facades\Action;
use App\Modules\Facades\Module;
use App\Modules\Routing\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Modules\Developer\Http\Requests\ModuleRequest;

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
        $this->modules = Module::all();

        return $this->view();
    }

    /**
     * 展示
     * 
     * @return Response
     */
    public function show(Request $request, $module)
    {
        if (! Module::has($module)) {
            return redirect(route('developer.module.index'));
        }

        $this->module = Module::find($module);                
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
        $style = $request->input('style');

        // 生成模块
        Artisan::call("module:make", [
            'module'  => $name,
            '--force' => false,
        ]);

        // 生成钩子
        Action::fire("module.make.{$style}", $name);

        return $this->success(trans('master.created'),route('developer.module.index'));
    }

    /**
     * 编辑单个JSON字段
     *
     * @return Response
     */
    public function update(Request $request, $module, $field)
    {
        $rule = 'required';

        if ($field == 'order') {
            $rule = 'required|numeric|min:1';
        }

        if ($field == 'version') {
            $rule = ['required','regex:/^[0-9]+.[0-9]+.[0-9]+$/'];
        }

        $this->validate($request, ['newvalue'=>$rule], [], ['newvalue'=>'']);


        $path    = Module::findorFail($module)->getPath('module.json');
        $content = array_merge(json_decode(File::get($path), true), [
            $field => $request->input('newvalue')
        ]);

        File::put($path, json_encode($content, JSON_PRETTY_PRINT));

        return $this->success(trans('master.saved'),route('developer.module.show',[$module]));
    }
}
