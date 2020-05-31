<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-request
                {module : The module to use}
                {name : The name to use}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = 'Request';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'request';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'request';

    /**
     * 生成前准备
     * @return boolean
     */
    public function prepare()
    {
        $this->replace([
            'lang_name'      => $this->getLowerNameInput(),
        ]);

        return true;
    }
}
