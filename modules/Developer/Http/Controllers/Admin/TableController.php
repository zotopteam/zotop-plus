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

        $table = Table::find('test');
        $table->drop();
        $table->create([
            ['name'=>'id', 'type'=>'bigint', 'length'=>'', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'increments', 'index'=>'', 'default'=>'', 'comment'=>''],
            ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
            ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
            ['name'=>'content', 'type'=>'text', 'length'=>'100', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'money'],
            ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
            ['name'=>'sort', 'type'=>'mediumInteger', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'3', 'comment'=>'status'],
        ],[
            ['name'=>'sort_status','type'=>'index','columns'=>['sort','status']]
        ]);       

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
            ['name'=>'title', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'unique', 'default'=>'', 'comment'=>'标题'],
            ['name'=>'image', 'type'=>'char', 'length'=>'10', 'nullable'=>'nullable', 'unsigned'=>'', 'increments'=>'', 'index'=>'index', 'default'=>'', 'comment'=>'ttttt'],
            ['name'=>'content', 'type'=>'text', 'length'=>'100', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>'money'],
            ['name'=>'money', 'type'=>'float', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'0.0', 'comment'=>'money'],
            ['name'=>'sort', 'type'=>'mediumint', 'length'=>'10', 'nullable'=>'', 'unsigned'=>'unsigned', 'increments'=>'', 'index'=>'', 'default'=>'0', 'comment'=>'sort'],
            ['name'=>'status', 'type'=>'boolean', 'length'=>'1', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'3', 'comment'=>'status'],            
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
        $migrate->createTableMigration(true);

        $table = Table::find($table);

        $this->columns = $table->columns();
        $this->indexes = $table->indexes();

        return $this->view();
    }

    public function operate(Request $request, $module, $table, $action)
    {
        if (in_array($action, ['rename'])) {
            $request->validate(['name'=>['required', 'string', new TableName($module)]],[],['name'=>trans('developer::table.name')]);            
        }

        $migrate = new Migrate($module, $table);

        $table = Table::find($table);
        $columns = $table->columns();

        switch ($action) {
            case 'rename':
                $table->rename($request->name);
                $migrate->put(['action'=>$action,'from'=>$table->name(), 'to'=>$request->name]);
                break;
            case 'dropColumn':
                $table->dropColumn($request->name);
                $migrate->put(['action'=>$action, 'column'=>$columns[$request->name]]);
                break;                
            default:
                # code...
                break;
        }

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
    public function migration($module, $table)
    {
        //$migrate = new Migrate($module, $table);

        // 组装生成文件名称
        $table = strtolower($table);
        $path = app('modules')->getModulePath($module).'Database/Migrations/';
        $path = realpath($path);
        
        $schema = new \Modules\Core\Support\Migration\Schema();
        
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
    public function columns(Request $request, $action='')
    {        
        $columns = $request->input('columns', []);

        // 默认字段结构
        $default = ['name'=>'', 'type'=>'varchar', 'length'=>'', 'nullable'=>'', 'unsigned'=>'', 'increments'=>'', 'index'=>'', 'default'=>'', 'comment'=>''];

        // 补充传入数据结构
        $columns = collect($columns)->map(function ($column, $key) use($default) {
            return $column + $default;
        });

        // 添加时字段数组尾部增加一条数据
        if ($action == 'add') {
            $columns->push($default);
        }

        // 添加时间戳 created_at 和 updated_at
        if ($action == 'add_timestamps') {

            // 检查 created_at 和 updated_at 是否已经存在
            if ($columns->where('name','created_at')->where('type','timestamp')->count() > 0 && $columns->where('name','updated_at')->where('type','timestamp')->count() > 0) {
                abort(403, trans('core::master.existed'));
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
                abort(403, trans('core::master.existed'));
            }

            // 为防止字段类型被更改，过滤 deleted_at 并重新添加
            $columns = $columns->filter(function($column, $key) {
                return $column['name'] != 'deleted_at';
            });

            $columns[] = array_merge($default, ['name'=>'deleted_at','type'=>'timestamp']);
        }

        $this->increments = $columns->where('increments','increments')->first()['name'];
        $this->columns    = $columns->toArray();

        //debug($this->columns);

        return $this->view();
    }    
}
