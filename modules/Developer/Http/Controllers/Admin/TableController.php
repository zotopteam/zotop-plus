<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Developer\Support\Table;
use Modules\Core\Support\Migration\Schema;
use Module;
use Artisan;

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

    /**
     * 从已有表生成为migration迁移文件
     * @return Response
     */
    public function migration($module, $table)
    {
        // 组装生成文件名称
        $table = strtolower($table);
        $path = app('modules')->getModulePath($module).'Database/Migrations/';
        $path = realpath($path);
        
        $schema = new Schema();

        // $fields = $schema->getFields($table);
        // debug($fields);
        
        $filename = $schema->getFileName($table, 'create');

        // 删除已经存在的文件，TODO：数据表中已经迁移问题处理
        foreach (app('files')->files($path) as $file) {
            if (strpos($file, $filename)) {
                app('files')->delete($file);
            }
        }

        $filepath = $path.'/'.date('Y_m_d_His', strtotime( '+1 second' )).'_'.$filename.'.php';

        $create = $schema->getCreateTable($table);

        app('files')->put($filepath, $create);
        
        return $this->success(trans('core::master.created'));
    }
}
