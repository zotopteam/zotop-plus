<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use App\Modules\Maker\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
                {--fields= : The fields to down}
                {--migrate : Migrate the created file.}                
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
     * 迁移名称前缀
     * @var string
     */
    protected $prefix;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table  = $this->getTableName();
        $this->fields = $this->getFields();
        $this->prefix = date('Y_m_d_His');

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
     * 迁移后执行
     * @return void
     */
    public function generated()
    {
        if ($this->option('migrate')) {

            $path = $this->getFilePath();

            $this->call('migrate:files', [
                'files'   => $this->getModulePath($path),
                '--force' => true,
            ]);
        }
    }

    /**
     * 获取表名称
     * @return string
     */
    public function getTableName()
    {
        return $this->getLowerNameInput();
    }

    /**
     * 获取输入的字段
     * @return string
     */
    public function getFields()
    {
        $fields = $this->option('fields');

        if (empty($fields) ) {
            $table = Table::find($this->getTableName());
            if ($table->exists()) {
                $fields = $table->getBlueprints();
            }
        }

        return $fields;
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
        return "{$this->prefix}_drop_{$this->table}_table.{$this->extension}";
    }
}
