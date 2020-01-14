<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class MigrationMakeCreateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration-create
                {module : The module to use}
                {table : The table name to migrate}
                {fields? : The fields to up}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new create migration file for the specified module.'; 

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
    protected $stub = 'migration/create';

    /**
     * 迁移的字段
     * @var string
     */
    protected $fields;

    /**
     * 迁移的表名称，不含前缀
     * @var string
     */
    protected $table;

    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        $this->table  = strtolower($this->argument('table'));
        $this->fields = $this->argument('fields');

        if (! empty($this->fields)) {
            $this->stub = 'migration/create_fields';
        }

        // 检查是否存在同类的迁移，比如同一个表的多个创建
        if ($migrations = $this->mgirationCreated()) {

            if (! $this->option('force')) {
                $this->error('Table\'s migration already exist');
                return false;          
            }

            $this->deleteFiles($migrations);
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
        return Str::studly("create_{$this->table}_table");
    }
    
    /**
     * 重载文件名称
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His')."_create_{$this->table}_table.{$this->extension}";
    }

    /**
     * 获取已经创建的迁移
     * @return array
     */
    public function mgirationCreated()
    {
        $path       = $this->laravel['config']->get("modules.paths.dirs.{$this->dirKey}");
        $path       = $this->getModulePath($path);
        $pattern    = $path.DIRECTORY_SEPARATOR.'*.'.$this->extension;
        $migrations = $this->laravel['files']->glob($pattern);

        $names = [
            "create_{$this->table}_table",
            "update_{$this->table}_table",
            "drop_{$this->table}_table"
        ];

        foreach ($migrations as $key => $value) {
            if (! Str::contains($value, $names)) {
                unset($migrations[$key]);
            }            
        }

        return $migrations;
    }
}
