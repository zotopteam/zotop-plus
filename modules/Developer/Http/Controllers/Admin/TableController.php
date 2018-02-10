<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Developer\Support\Table;
use Module;

class TableController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index($module)
    {
        $this->module  = module($module);

        // 获取模块的数据表
        $this->tables = Table::module($module);

        $this->title = trans('developer::table.title');

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create($module)
    {
        $this->module  = module($module);

        $this->title = trans('developer::table.create');

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $table = new Table;
        $table->fill($request->all());
        $table->save();

        return $this->success(trans('core::master.created'), route('developer.table.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->title = trans('developer::developer.show');

        $this->table = Table::findOrFail($id);

        return $this->view();
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title = trans('developer::developer.edit');
        $this->id    = $id;
        $this->table = Table::findOrFail($id);

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $table->fill($request->all());        
        $table->save();

        return $this->success(trans('core::master.updated'), route('developer.table.index'));
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        return $this->success(trans('core::master.deleted'), route('developer.table.index'));        
    }
}
