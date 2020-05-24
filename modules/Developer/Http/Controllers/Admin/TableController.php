<?php

namespace Modules\Developer\Http\Controllers\Admin;


use App\Modules\Facades\Module;
use App\Modules\Maker\Structure;
use App\Modules\Maker\Table;
use App\Modules\Routing\AdminController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Developer\Rules\TableName;


class TableController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */  
    public function index($module)
    {
        $this->module = Module::findOrFail($module);
        $this->tables = Table::all();
        $this->title = trans('developer::table.title');

        $this->tables = array_filter($this->tables, function($table) {
            return $table == $this->module->getLowerName() || Str::startsWith($table, $this->module->getLowerName().'_');
        });

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create(Request $request, $module)
    {        
        $module  = Module::findOrFail($module);

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
            $columns = $request->input('columns');
            $indexes = $request->input('indexes', []);

            $fields = Structure::instance($columns, $indexes)->getBluepoints();

            Artisan::call('module:make-migration-create', [
                'module'    => $module->getLowerName(),
                'name'      => $name,
                '--fields'  => $fields,
                '--migrate' => true,
                '--force'   => false
            ]);    

            if (Table::find($name)->exists()) {
                return $this->success(trans('master.created'), route('developer.table.manage', [$module, $name]));
            }

            return $this->error(trans('master.create.failed'));
        }

        // 读取新建表的默认数据
        $default = Module::data('developer::table.default', ['module'=>$module]);

        $this->module  = $module;
        $this->name    = $default['name'];
        $this->columns = $default['columns'];
        $this->indexes = $default['indexes'];

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
    public function manage(Request $request, $module, $table)
    {
        $this->title          = trans('developer::table.manage');
        $this->table          = Table::findOrFail($table);
        $this->module         = Module::findOrFail($module);
        $this->migrations     = [];
        $this->columns        = $this->table->columns();
        $this->indexes        = $this->table->indexes();
        $this->primaryColumns = $this->indexes->where('type','primary')->first()['columns'];
        $this->indexColumns   = $this->indexes->where('type','index')->pluck('columns')->flatten()->all();
        $this->uniqueColumns  = $this->indexes->where('type','unique')->pluck('columns')->flatten()->all();
        $this->isModelFile    = $this->isModelFile($this->module, $this->table);
        $this->migrations     = $this->getMigrations($this->module, $this->table);

        return $this->view();
    }


   /**
     * 编辑，TODO:未完成
     * 
     * @return Response
     */
    public function edit(Request $request, $module, $table)
    {
        $module = Module::findOrFail($module);

        // 保存数据
        if ($request->isMethod('POST')) {

            $name    = strtolower($request->input('name'));

            // 如果重命名，则验证新名称
            $request->validate([
                'name'    => ($name==$table) ? ['required', 'string'] : ['required', 'string', new TableName($module)],
                'columns' => ['required', 'array'],
            ],[],[
                'name'    => trans('developer::table.name'),
                'columns' => trans('developer::table.columns'),
            ]);

            return $this->success(trans('master.updated'), route('developer.table.manage', [$module, $name]));
        }

        $table = Table::findOrFail($table);

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
        $module = Module::findOrFail($module);
        $table  = Table::findOrFail($table);

        Artisan::call('module:make-migration-drop', [
            'module'    => $module->getLowerName(),
            'name'      => $table->name(),
            '--migrate' => true,
            '--force'   => true,
        ]);

        return $this->success(trans('master.deleted'), route('developer.table.index',[$module]));        
    }

    /**
     * 从已有表生成为migration迁移文件
     * @return Response
     */
    public function migration($module, $table, $action='create')
    {
        $module = Module::findOrFail($module);
        $table  = Table::findOrFail($table);
        
        Artisan::call("module:make-migration-{$action}", [
            'module'    => $module->getLowerName(),
            'name'      => $table->name(),
            '--migrate' => false,
            '--force'   => true,
        ]);          
        
        return $this->success(
            trans('developer::table.migration.created')."\r\n".Artisan::output(),
            route('developer.migration.index',[$module])
        );
    }

    /**
     * 从已有表生成为model文件
     * @return Response
     */
    public function model($module, $table, $force=null)
    {
        $module = Module::findOrFail($module);
        $table  = Table::findOrFail($table);
        
        Artisan::call('module:make-model', [
            'module'  => $module->getLowerName(),
            'name'    => $this->getModelName($module, $table),
            '--table' => $table->name(),
            '--force' => (boolean) $force
        ]);  
        
        return $this->success(trans('master.operated'), route('developer.table.manage',[$module, $table]));
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
        $structure = Structure::instance(
            $request->input('columns', []),
            $request->input('indexes', [])
        );

        // 添加空白行
        if ($action == 'addBlank') {
            $structure->addColumn([]);
        }

        // 添加时间戳
        if ($action == 'addTimestamps') {
            if (! $structure->getColumn('created_at')) {
                $structure->addColumn(['name'=>'created_at','type'=>'timestamp','nullable'=>1]);
            }
            if (! $structure->getColumn('updated_at')) {
                $structure->addColumn(['name'=>'updated_at','type'=>'timestamp','nullable'=>1]);
            }
        }

        // 添加软删除
        if ($action == 'addSoftdeletes' && !$structure->getColumn('deleted_at')) {
            $structure->addColumn(['name'=>'deleted_at','type'=>'timestamp','nullable'=>1]);
        }

        // 添加索引
        if (in_array($action, ['primary','index','unique'])) {
            $columns = $structure->columns()->where('select', 1)->pluck('name')->all();
            if (empty($columns)) {
                 abort(422, trans('developer::table.index.unselect'));
            }
            $structure->addIndex(['type'=>$action,'columns'=>$columns]);
        }           

        $this->columns    = $structure->columns();
        $this->indexes    = $structure->indexes();
        $this->increments = $structure->increments();
        $this->primary    = $structure->primary();

        return $this->view();
    }

    /**
     * 获取表的模型名称，去掉表名称开始的模块名称+下划线，转换为变种驼峰
     * @param  Table $table
     * @return string
     */
    private function getModelName($module, $table)
    {
        $name = Str::after($table->name(), $module->getLowerName().'_');

        return Str::studly($name);
    }

    /**
     * 检查表当前模块下对应的模型是否存在
     * @param  Module  $module
     * @param  Table  $table
     * @return boolean
     */
    private function isModelFile($module, $table)
    {
        $path = $module->getPath('model', true).DIRECTORY_SEPARATOR.$this->getModelName($module, $table).'.php';

        return File::isFile($path);
    }

    /**
     * 获取当前模块下和表相关的迁移文件
     * @param  Module  $module
     * @param  Table  $table
     * @return boolean
     */
    private function getMigrations($module, $table)
    {
        $migrations = File::glob($module->getPath('migration', true).'/*.php');

        foreach ($migrations as $key => $file) {
            // 获取迁移文件内容
            $content = File::get($file);
            // 检查迁移文件中是否有 Schema::XXX(’tablename‘ 内容，有则是该表的相关迁移文件
            if (! preg_match('/Schema::(\w+)\(\''.$table.'\'/i', $content, $matches) ) {
                unset($migrations[$key]);
            }
        }

        return $migrations;
    }
}
