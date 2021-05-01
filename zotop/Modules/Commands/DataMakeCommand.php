<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Maker\GeneratorCommand;

class DataMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-data
                {module : The module to use}
                {name : The name to use}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new file data for the specified module.';

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
    protected $dirKey = 'data';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'data';

    /**
     * 获取文件名称，data文件的文件名为全部小写
     *
     * @return string
     */
    protected function getFileName()
    {
        return $this->getLowerNameInput() . '.' . $this->extension;
    }
}
