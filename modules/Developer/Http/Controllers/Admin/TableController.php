<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Developer\Support\Table;
use Modules\Developer\Support\Migrate;
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

        // $table = Table::find('test');
        // $table->drop();
        // $table->create([
        //     ['name'=>'id', 'type'=>'bigint', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
        //     ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
        //     ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
        //     ['name'=>'content', 'type'=>'text', 'length'=>'100', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'money'],
        //     ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
        //     ['name'=>'sort', 'type'=>'mediumInteger', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
        //     ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'3', 'comment'=>'status'],
        // ],[
        //     ['name'=>'sort_status','type'=>'index','columns'=>['sort','status']]
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

            $table = Table::find($name);
            $table->create($columns);

            if ($table->exists()) {
                return $this->success(trans('core::master.created'), route('developer.table.structure', [$module, $name]));
            }

            return $this->error(trans('core::master.create.failed'));
        }

        $this->name   = 'test';
        $this->columns = [
            ['name'=>'id', 'type'=>'int', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
            // ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
            // ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
            // ['name'=>'content', 'type'=>'text', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'money'],
            // ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
            ['name'=>'sort', 'type'=>'mediumint', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'3', 'comment'=>'status'],            
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

        $this->columns = $table->columns();
        $this->indexes = $table->indexes();
        $this->primary = collect($this->indexes)->where('type','primary')->first()['columns'];

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
        $table = new Table();

        $default = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];

        $columns = $request->input('columns', []);
        $indexes = $request->input('indexes', []);

        // 默认字段结构
        $columns = $table->formatColumns($columns);
        $indexes = $table->formatIndexes($indexes);

        // 集合
        $columns = collect($columns);
        $indexes = collect($indexes);

        //自增
        $increments = $columns->where('increments','increments')->first()['name'];

        // 添加时字段数组尾部增加一条数据
        if ($action == 'add') {
            $columns->push($default);
        }

        // 添加时间戳 created_at 和 updated_at
        if ($action == 'add_timestamps') {

            // 检查 created_at 和 updated_at 是否已经存在
            if ($columns->where('name','created_at')->where('type','timestamp')->count() > 0 && $columns->where('name','updated_at')->where('type','timestamp')->count() > 0) {
                abort(403, trans('developer::table.column.exists'));
            }

            // 为防止字段类型被更改，过滤 created_at 和 updated_at 并重新添加
            $columns = $columns->filter(function($column, $key) {
                return ! in_array($column['name'], ['created_at', 'updated_at']);
            });

            $columns->push(array_merge($default, ['name'=>'created_at','type'=>'timestamp']));
            $columns->push(array_merge($default, ['name'=>'updated_at','type'=>'timestamp']));
        }

        // 添加软删除
        if ($action == 'add_softdeletes') {

            // 检查 deleted_at 是否已经存在
            if ($columns->where('name','deleted_at')->where('type','timestamp')->count() > 0) {
                abort(403, trans('developer::table.column.exists'));
            }

            // 为防止字段类型被更改，过滤 deleted_at 并重新添加
            $columns = $columns->filter(function($column, $key) {
                return $column['name'] != 'deleted_at';
            });

            $columns[] = array_merge($default, ['name'=>'deleted_at','type'=>'timestamp']);
        }

        // 添加索引
        if (in_array($action, ['primary','index','unique'])) {
            
            $indexColumns = $columns->where('select', 'select')->pluck('name')->all();
            $indexName    = implode('_', array_sort($indexColumns));

            if (empty($indexColumns)) {
                abort(403, trans('developer::table.index.unselect'));
            }

            if ($indexes->where('name', $indexName)->count() > 0) {
                abort(403, trans('developer::table.index.exists'));
            }

            $indexes->push([
                'type'    => $action,
                'columns' => $indexColumns,
                'name'    => $indexName,
            ]);
        }

        // 索引处理
        $indexes = $indexes->filter(function($value, $key) use ($columns, $increments) {
            // 如果有自增，过滤掉主键
            if ($increments && $value['type'] == 'primary') {
                return false;
            }
            // 去掉包含不存在字段的索引
            $names = $columns->pluck('name')->all();
            $exists = true;
            foreach ($value['columns'] as $v) {
                if (! in_array($v, $names)) {
                    $exists = false;
                    break;
                }
            }
            return $exists;
        });

        $this->columns    = $columns->toArray();
        $this->indexes    = $indexes->toArray();
        $this->increments = $increments;         

        //debug($this->columns);

        return $this->view();
    }    
}
