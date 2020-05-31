<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
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
                {name : The table name to migrate}
                {--fields_up= : The fields to up}
                {--fields_down= : The fields to down}
                {--migrate : Migrate the created file.}                
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
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'migration';

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
     * 迁移名称前缀
     * @var string
     */
    protected $prefix;

    /**
     * 迁移名称后缀，更新表可以存在多个，增加一个随机字符串
     * @var string
     */
    protected $append;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table  = $this->getTableName();
        $this->prefix = date('Y_m_d_His');
        $this->append = date('YmdHis');

        parent::handle();
    }

    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        // 替换信息
        $this->replace([
            'table_name'  => $this->table,
            'fields_up'   => $this->getFieldsUp(),
            'fields_down' => $this->getFieldsDown(),
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
     * 获取迁移的字段
     * @return string
     */
    public function getFieldsUp()
    {
        return $this->option('fields_up');
    }

    /**
     * 获取回滚的字段
     * @return string
     */
    public function getFieldsDown()
    {
        return $this->option('fields_down');
    }

    /**
     * 存在多个update, 类名随机
     * @return string
     */
    public function getClassName()
    {
        return Str::studly("update_{$this->table}_table_{$this->append}");
    }

    /**
     * 重载文件名称
     * @return string
     */
    public function getFileName()
    {
        return "{$this->prefix}_update_{$this->table}_table_{$this->append}.{$this->extension}";
    }
}
