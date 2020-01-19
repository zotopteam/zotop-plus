<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class MigrationMakeDropCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration-drop
                {module : The module to use}
                {name : The table name to migrate}
                {fields? : The fields to down}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new drop migration file for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = '';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'migration';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'migration/drop';

    /**
     * 迁移的表名称，不含前缀
     * @var string
     */
    protected $table;

    /**
     * 迁移的字段
     * @var string
     */
    protected $fields;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table  = strtolower($this->argument('name'));
        $this->fields = $this->argument('fields');

        parent::handle();        
    }   

    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        if (! empty($this->fields)) {
            $this->stub = 'migration/drop_fields';
        }

        // 替换信息
        $this->replace([
            'table_name' => $this->table,
            'fields'     => $this->fields,
        ]);

        return true;
    }

    /**
     * 存在多个update, 类名随机
     * @return string
     */
    public function getClassName()
    {
        return Str::studly("drop_{$this->table}_table");
    }
    
    /**
     * 重载文件名称
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His')."_drop_{$this->table}_table.{$this->extension}";
    }
}
