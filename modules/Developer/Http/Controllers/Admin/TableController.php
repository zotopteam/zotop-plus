<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Developer\Support\Table;
use Modules\Developer\Support\Migrate;
use Modules\Developer\Support\Structure;
use Modules\Developer\Rules\TableName;
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

        $tables = Table::all();

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

        // 测试表创建
        // $table = Table::find('test');
        // $table->drop();
        // $table->create([
        //     ['name'=>'id', 'type'=>'bigint', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
        //     ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
        //     ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
        //     ['name'=>'content', 'type'=>'text', 'length'=>'100', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'money'],
        //     ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
        //     ['name'=>'sort', 'type'=>'mediumInteger', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
        //     ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'3', 'comment'=>'status'],
        // ],[
        //     ['type'=>'index', 'columns'=>['money']],
        //     ['type'=>'index','columns'=>['sort','status']]
        // ]);       

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create(Request $request, $module)
    {        
        $this->module  = module($module);

        // 保存数据
        if ($request->isMethod('POST')) {

            $request->validate([
                'name'    => ['required', 'string', new TableName($module)],
                'columns' => ['required', 'array'],
            ],[],[
                'name'    => trans('developer::table.name'),
                'columns' => trans('developer::table.columns'),
            ]);

            $name    = $request->input('name');

            // 创建迁移文件并迁移
            $migrate = Migrate::instance($module, $name, Structure::instance(
                $request->columns,
                $request->indexes
            ));

            $migrate->createTable(true);

            if (Table::find($name)->exists()) {
                return $this->success(trans('core::master.created'), route('developer.table.structure', [$module, $name]));
            }

            return $this->error(trans('core::master.create.failed'));
        }

        $this->name   = 'test';
        $this->columns = [
            ['name'=>'id', 'type'=>'int', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
            ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
            ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
            ['name'=>'content', 'type'=>'text', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'money'],
            ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
            ['name'=>'sort', 'type'=>'mediumint', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'1', 'comment'=>'status'],            
        ];

        $this->indexes = [
            ['name'=>'sort_status','type'=>'index','columns'=>['sort','status']]
        ];


        $this->title = trans('developer::table.create');

        return $this->view();
    }

    /**
     * 表结构
     * 
     * @param  Request $request
     * @param  string  $module  module name
     * @param  string  $table   table name
     * @return Response
     */
    public function structure(Request $request, $module, $table)
    {
        // 获取数据表字段、索引和主键
        $table   = Table::find($table);

        // 获取迁移文件
        $migrate = Migrate::instance($module, $table);

        $this->title          = trans('developer::table.structure');
        $this->table          = $table;
        $this->module         = $module;
        $this->migrations     = $migrate->getMigrationFiles();
        $this->columns        = $table->columns();
        $this->indexes        = $table->indexes();
        $this->primaryColumns = collect($this->indexes)->where('type','primary')->first()['columns'];
        $this->indexColumns   = collect($this->indexes)->where('type','index')->pluck('columns')->flatten()->all();
        $this->uniqueColumns  = collect($this->indexes)->where('type','unique')->pluck('columns')->flatten()->all();

        return $this->view();
    }


   /**
     * 编辑
     *
     * @return Response
     */
    public function edit(Request $request, $module, $table)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            $name    = $request->input('name');

            // 如果重命名，则验证新名称
            if ($name != $table) {
                $request->validate([
                    'name'    => ['required', 'string', new TableName($module)],
                    'columns' => ['required', 'array'],
                ],[],[
                    'name'    => trans('developer::table.name'),
                    'columns' => trans('developer::table.columns'),
                ]);                
            }

            // 创建更新并迁移
            $migrate = Migrate::instance($module, $table, Structure::instance(
                $request->input('columns', []),
                $request->input('indexes', [])
            ));

            $migrate->updateTable($name);

            return $this->success(trans('core::master.updated'), route('developer.table.structure', [$module, $name]));
        }   

        $table = Table::find($table);

        $this->columns = $table->columns();
        $this->indexes = $table->indexes();
        $this->title   = trans('developer::table.edit');
        $this->module  = $module;
        $this->name    = $table->name();
        $this->columns = $table->columns();
        $this->indexes = $table->indexes();

        return $this->view();
    }    

    /**
     * 删除
     *
     * @return Response
     */
    public function drop(Request $request, $module, $table)
    {
        // $table = Table::find($table);
        // $table->drop();

        $migrate = Migrate::instance($module, $table);
        $migrate->dropTable(); 

        return $this->success(trans('core::master.deleted'), route('developer.table.index',[$module]));        
    }

    /**
     * 从已有表生成为migration迁移文件
     * @return Response
     */
    public function migration($module, $table, $action)
    {
        $migrate = new Migrate($module, $table);

        if ($action == 'override') {
            $migrate->createTable(true);
        }

        if ($action == 'create') {
           $migrate->createTable(false); 
        }

        if ($action == 'update') {
            $migrate->updateTable();
            return $this->success(trans('core::master.operated'));

        }        
        
        return $this->success(trans('core::master.operated'), route('developer.table.structure',[$module, $table]));
    }

    /**
     * 字段显示和添加
     * 
     * @param  Request $request
     * @param  string  $action  动作
     * @return Response
     */
    public function columns(Request $request, $action='')
    {
        $structure = new Structure(
            $request->input('columns', []),
            $request->input('indexes', [])
        );

        if (in_array($action,['addBlank','addTimestamps','addSoftdeletes'])) {
            $structure->$action();
        }

        // 添加索引
        if (in_array($action, ['primary','index','unique'])) {
            if ($columns = $structure->columns()->where('select', 'select')->pluck('name')->all()) {
                $structure->addIndex(['type'=>$action,'columns'=>$columns]);
            } else {
                abort(403, trans('developer::table.index.unselect'));
            }
        }    

        $this->columns    = $structure->columns();
        $this->indexes    = $structure->indexes();
        $this->increments = $structure->increments();
        $this->primary    = collect($this->indexes)->where('type','primary')->first();

        //debug($this->columns);

        return $this->view();
    }
}
