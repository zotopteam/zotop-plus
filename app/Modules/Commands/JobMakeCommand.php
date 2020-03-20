<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class JobMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-job
                {module : The module to use}
                {name : The name to use}
                {--sync : Indicates that job should be synchronous.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = '';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'jobs';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'job';

    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        if ($this->option('sync')) {
            $this->stub = 'job_sync';
        }
        
        return true;
    }

}
