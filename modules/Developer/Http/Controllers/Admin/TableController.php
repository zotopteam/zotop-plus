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
            $columns = $request->input('columns', []);
            $indexes = $request->input('indexes', []);            

            $table = Table::find($name);
            $table->create($columns, $indexes);

            if ($table->exists()) {
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


    public function structure(Request $request, $module, $table)
    {
        $this->title  = trans('developer::table.structure');
        $this->table  = $table;
        $this->module = $module;

        $migrate = new Migrate($module, $table);

        // 获取迁移文件
        $this->migrations = $migrate->getMigrationFiles();

        // 获取更新日志
        $this->updatelogs = $migrate->get();

        // 获取数据表字段、索引和主键
        $table = Table::find($table);

        $this->columns        = $table->columns();
        $this->indexes        = $table->indexes();
        $this->primaryColumns = collect($this->indexes)->where('type','primary')->first()['columns'];
        $this->indexColumns   = collect($this->indexes)->where('type','index')->pluck('columns')->flatten()->all();
        $this->uniqueColumns  = collect($this->indexes)->where('type','unique')->pluck('columns')->flatten()->all();

        return $this->view();
    }

    public function operate(Request $request, $module, $table, $action)
    {
        if (in_array($action, ['rename'])) {
            $request->validate(['name'=>['required', 'string', new TableName($module)]],[],['name'=>trans('developer::table.name')]);            
        }

        $table = Table::find($table);
        $columns = $table->columns();
        $indexes = $table->indexes();

        // 迁移日志
        // 迁移日志参数
        $arguments = [];

        switch ($action) {
            case 'rename':
                $table->rename($request->name);
                $arguments['from'] = $table->name();
                $arguments['to']   = $request->name;
                break;
            case 'dropColumn':
                $table->dropColumn($request->name);
                $arguments['column'] = $columns[$request->name];
                break;
            case 'timestamps':
                $table->timestamps();
                break;                               
            default:
                # code...
                break;
        }

        // 保存迁移日志
        $migrate = new Migrate($module, $table);
        $migrate->put(['action'=>$action, 'arguments'=>$arguments]);

        return $this->success(trans('core::master.operated'), route('developer.table.structure',[$module, $table->name()])); 
    }

    /**
     * 重命名
     *
     * @return Response
     */
    public function rename(Request $request, $module, $table)
    {
        $request->validate([
            'name'   => ['required', 'string', new TableName($module)],
        ],[],[
            'name' => trans('developer::table.name'),
        ]);

        $name = $request->input('name');

        $table = Table::find($table);
        $table->rename($name);

        return $this->success(trans('core::master.operated'), route('developer.table.index',[$module]));        
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function drop(Request $request, $module, $table)
    {
        $table = Table::find($table);
        $table->drop();

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
            $migrate->createTableMigration(true);
        }

        if ($action == 'create') {
           $migrate->createTableMigration(false); 
        }

        if ($action == 'update') {
            $migrate->updateTableMigration();
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

        //debug($this->columns);

        return $this->view();
    }
}
