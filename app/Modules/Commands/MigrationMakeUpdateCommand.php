<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class MigrationMakeUpdateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration-update
                {module : The module to use}
                {table : The table name to migrate}
                {fields_up? : The fields to up}
                {fields_down? : The fields to down}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new update migration file for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = '';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$pathDirKey}“)
     * @var null
     */
    protected $pathDirKey = 'migration';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'migration/update';

    /**
     * 迁移的表名称，不含前缀
     * @var string
     */
    protected $table;

    /**
     * 更新表可以存在多个，增加一个随机字符串
     * @var [type]
     */
    protected $random;

    /**
     * 重载prepare
     * @return void
     */
    public function prepare()
    {
        $this->table  = strtolower($this->argument('table'));
        $this->random = date('YmdHis');

        // 替换信息
        $this->replace([
            'class_name'  => $this->getClassName(),
            'table_name'  => $this->table,
            'fields_up'   => $this->argument('fields_up'),
            'fields_down' => $this->argument('fields_down'),
        ]);

        return true;
    }

    /**
     * 存在多个update, 类名随机
     * @return string
     */
    public function getClassName()
    {
        return Str::studly("update_{$this->table}_table_{$this->random}");
    }
    
    /**
     * 重载文件名称
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His')."_update_{$this->table}_table_{$this->random}.{$this->extension}";
    }
}
