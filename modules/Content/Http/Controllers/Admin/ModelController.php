<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Content\Models\Model;
use Modules\Content\Http\Requests\ModelRequest;
use Modules\Content\Support\ModelHelper;
use Module;
use File;

class ModelController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('content::model.title');

        $this->models = Model::with('user')->orderby('sort','asc')->get();

        $this->import = ModelHelper::getImport($this->models->pluck('id')->all());

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('content::model.create');

        $this->model = Model::findOrNew('');
        $this->model->icon = 'fas fa-file';

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(ModelRequest $request)
    {                
        $model = new Model;
        $model->fill($request->all());
        $model->sort = Model::max('sort') + 1;

        debug($request->nestable);

        if ($model->save()) {
            ModelHelper::fieldInit($model->id);
        }

        return $this->success(trans('core::master.created'), route('content.model.index'));
    } 

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('content::model.edit');
        $this->id    = $id;
        $this->model = Model::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(ModelRequest $request, $id)
    {
        $model = Model::findOrFail($id);
        $model->fill($request->all());       
        $model->save();

        return $this->success(trans('core::master.updated'), route('content.model.index'));
    }

    /**
     * 启用和禁用
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        foreach ($request->ids as $sort=>$id) {
            Model::where('id', $id)->update([
                'sort' => $sort
            ]);
        }

        return $this->success(trans('core::master.operated'));
    }  

    /**
     * 启用和禁用
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $model = Model::findOrFail($id);
        $model->disabled = $model->disabled ? 0 : 1;
        $model->save();

        return $this->success(trans('core::master.operated'), route('content.model.index'));
    }   

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {

        $model = Model::findOrFail($id);
        $model->delete();

        return $this->success(trans('core::master.deleted'), route('content.model.index'));        
    }

    /**
     * 模型导出
     * 
     * @param  string $id 编号
     * @return download
     */
    public function export($id)
    {
        return ModelHelper::export($id); 
    }

    /**
     * 导入模型
     * @param  Request $request
     * @return json
     */
    public function import(Request $request)
    {
        ModelHelper::import($request->file); 

        return $this->success(trans('core::master.operated'), route('content.model.index'));
    }
}
