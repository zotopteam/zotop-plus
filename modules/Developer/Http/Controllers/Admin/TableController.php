<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
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
        $module  = module($module);

        $schema = new Schema();
        $tables = $schema->getTables();

        // 如果module.json  中包含 tables，优先获取
        if (is_array($module->tables)) {
            $moduleTables = $module->tables;
            $tables = array_filter($tables, function($table) use($moduleTables) {
                return in_array($table, $moduleTables);
            });
        } else {
            $moduleName = $module->getLowerName();
            $tables = array_filter($tables, function($table) use($moduleName) {
                return $table == $moduleName || starts_with($table, $moduleName.'_');
            });
        }        

        $this->module = $module;
        $this->tables = $tables;
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
        $this->fields = [
            ['name'=>'id', 'type'=>'int', 'length'=>'', 'nullable'=>'', 'unsigned'=>true, 'autoIncrement'=>true, 'index'=>'primary', 'default'=>'', 'comment'=>'']
        ];
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

    /**
     * 字段显示和添加
     * 
     * @param  Request $request
     * @param  string  $action  动作
     * @return Response
     */
    public function fields(Request $request, $action='')
    {
        $fields = $request->input('fields', []);

        $default = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'autoIncrement'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];

        $fields = collect($fields)->map(function ($field, $key) use($default) {
            return $field + $default;
        });

        // 添加时字段数组尾部增加一条数据
        if ($action == 'add') {
            $fields->push($default);
        }

        if ($action == 'add_timestamps') {

            if ($fields->where('name','created_at')->where('type','timestamp')->count() > 0 && $fields->where('name','updated_at')->where('type','timestamp')->count() > 0) {
                abort(403, trans('core::master.existed'));
            }

            $fields = $fields->filter(function($field, $key) {
                return ! in_array($field['name'], ['created_at', 'updated_at']);
            });

            $fields->push(array_merge($default, ['name'=>'created_at','type'=>'timestamp']));
            $fields->push(array_merge($default, ['name'=>'updated_at','type'=>'timestamp']));
        }

        if ($action == 'add_softdeletes') {

            if ($fields->where('name','deleted_at')->where('type','timestamp')->count() > 0) {
                abort(403, trans('core::master.existed'));
            }

            $fields = $fields->filter(function($field, $key) {
                return $field['name'] != 'deleted_at';
            });

            $fields[] = array_merge($default, ['name'=>'deleted_at','type'=>'timestamp']);
        }



        $this->fields = $fields->toArray();

        return $this->view();
    }    
}
