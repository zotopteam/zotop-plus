<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class SeederMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-seeder
                {module : The module to use}
                {name : The name to use}
                {--master : Indicates the seeder will created is a database seeder or table seeder.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder for the specified module.'; 


    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'seeder';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'seeder';


    /**
     * 生成前准备
     * @return boolean
     */
    public function prepare()
    {
        $this->appendName = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        return true;
    }
}
