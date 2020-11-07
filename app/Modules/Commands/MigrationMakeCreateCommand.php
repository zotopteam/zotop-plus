<?php

namespace App\Modules\Commands;

use App\Modules\Exceptions\ClassExistedException;
use App\Modules\Maker\GeneratorCommand;
use Facades\App\Modules\Maker\Table;
use Illuminate\Support\Str;

class MigrationMakeCreateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration-create
                {module : The module to use}
                {name : The table name to migrate}
                {--fields= : The fields to up}
                {--migrate : Migrate the created file.}
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
     *
     * @var null
     */
    protected $dirKey = 'migration';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'migration/create';

    /**
     * 迁移的字段
     *
     * @var string
     */
    protected $fields;

    /**
     * 迁移的表名称，不含前缀
     *
     * @var string
     */
    protected $table;

    /**
     * 迁移名称前缀
     *
     * @var string
     */
    protected $prefix;

    /**
     * Execute the console command.
     *
     * @return mixed|void
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        $this->table = $this->getTableName();
        $this->fields = $this->getFields();
        $this->prefix = date('Y_m_d_His');

        parent::handle();
    }

    /**
     * 重载prepare
     *
     * @return boolean
     * @throws \App\Modules\Exceptions\ClassExistedException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function prepare()
    {
        if (!empty($this->fields)) {
            $this->stub = 'migration/create_fields';
        }

        // 检查是否存在同类的迁移，比如同一个表的多个创建
        if ($migrations = $this->migrationCreated()) {

            if (!$this->option('force')) {

                if ($this->laravel->runningInConsole()) {
                    $this->error('Table\'s migration already exist');
                    return false;
                }

                throw new ClassExistedException("Table\'s migration already exist");
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
     * 迁移后执行
     *
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
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->getLowerNameInput();
    }

    /**
     * 获取输入的字段
     *
     * @return string
     */
    public function getFields()
    {
        $fields = $this->option('fields');

        // 如果未设置字段，则尝试获取表的字段
        if (empty($fields)) {
            $table = Table::find($this->getTableName());
            if ($table->exists()) {
                $fields = $table->getBlueprints();
                // TODO 由于表已经存在，生成迁移后，直接迁移生成文件
                //$this->input->setOption('migrate', true);
            }
        }

        return $fields;
    }

    /**
     * 存在多个update, 类名随机
     *
     * @return string
     */
    public function getClassName()
    {
        return Str::studly("create_{$this->table}_table");
    }

    /**
     * 重载文件名称
     *
     * @return string
     */
    public function getFileName()
    {
        return "{$this->prefix}_create_{$this->table}_table.{$this->extension}";
    }

    /**
     * 获取已经创建的迁移
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function migrationCreated()
    {
        $path = $this->laravel['config']->get("modules.paths.dirs.{$this->dirKey}");
        $path = $this->getModulePath($path);
        $pattern = $path . DIRECTORY_SEPARATOR . '*.' . $this->extension;
        $migrations = $this->laravel['files']->glob($pattern);

        foreach ($migrations as $key => $file) {
            // 获取迁移文件内容
            $content = $this->laravel['files']->get($file);
            // 检查迁移文件中是否有 Schema::XXX(’table_name‘ 内容，有则是该表的相关迁移文件
            if (!preg_match('/Schema::(\w+)\(\'' . $this->table . '\'/i', $content, $matches)) {
                unset($migrations[$key]);
            }
        }

        return $migrations;
    }
}
