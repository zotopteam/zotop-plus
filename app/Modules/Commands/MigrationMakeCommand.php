<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
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
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$pathDirKey}“)
     * @var null
     */
    protected $pathDirKey = 'migration';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'migration/blank';

    /**
     * 定义迁移文件名称
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His').'_'.strtolower($this->getArgumentName()).'.'.$this->extension;
    }

}
