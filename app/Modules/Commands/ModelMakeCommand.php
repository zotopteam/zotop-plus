<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class ModelMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-model
                {module : The module to use}
                {name : The name to use}
                {--table : The name to use is a table name.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified module.'; 


    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'model';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'model';


    /**
     * 生成前准备
     * @return boolean
     */
    public function prepare()
    {
        $this->replace([
            'table'    => $this->getTableName(),
            'fillable' => $this->getFillable(),
            'guarded'  => $this->getGuarded(),
        ]);

        return true;
    }

    public function getTableName()
    {
        $table = strtolower($this->getArgumentName());
        return $table;
    }

    public function getFillable()
    {
        // 从数据表中直接读取字段
        if ($this->option('table')) {

        }

        return '[]';
    }

    public function getGuarded()
    {
        // 从数据表中直接读取字段
        if ($this->option('table')) {

        }
                
        return '[]';
    }
}
