<?php

namespace App\Modules\Commands;

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
     *
     * @var null
     */
    protected $dirKey = 'seeder';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'seeder';

    
    /**
     * Execute the console command.
     *
     * @return mixed|void
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        $this->appendName = $this->option('master') ? 'DatabaseSeeder' : 'TableSeeder';

        parent::handle();
    }
}
