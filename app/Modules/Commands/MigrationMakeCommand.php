<?php

namespace App\Modules\Commands;

use App\Modules\Exceptions\ClassExistedException;
use App\Modules\Maker\GeneratorCommand;

class MigrationMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-migration
                {module : The module to use}
                {name : The name to migrate}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blank migration file for the specified module.';


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
    protected $stub = 'migration/blank';

    /**
     * 生成前准备
     *
     * @return bool
     * @throws \App\Modules\Exceptions\ClassExistedException
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function prepare()
    {
        if ($migration = $this->getMigrationCreatedAsThis()) {

            if (!$this->option('force')) {

                if ($this->laravel->runningInConsole()) {
                    $this->error("A {$this->getClassName()} class already exists. file: {$migration}");
                    return false;
                }

                throw new ClassExistedException("A {$this->getClassName()} class already exists. file: {$migration}");
            }

            $this->laravel['files']->delete($migration);
        }

        return true;
    }
    
    /**
     * 获取类名
     *
     * @return string
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function getClassName()
    {
        return $this->getStudlyNameInput();
    }

    /**
     * 定义迁移文件名称
     *
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His') . '_' . $this->getLowerNameInput() . '.' . $this->extension;
    }

    /**
     * 获取迁移目录已经存在的同名类文件
     *
     * @return string
     */
    public function getMigrationCreatedAsThis()
    {
        $path = $this->laravel['config']->get("modules.paths.dirs.{$this->dirKey}");
        $path = $this->getModulePath($path);
        $pattern = $path . DIRECTORY_SEPARATOR . '*.' . $this->extension;
        $migrations = $this->laravel['files']->glob($pattern);

        foreach ($migrations as $path) {
            require_once $path;

            if (class_exists($className = $this->getClassName())) {
                return $path;
            }
        }

        return null;
    }
}
