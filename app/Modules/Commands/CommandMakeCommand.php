<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;

class CommandMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-command
                {module : The module to use}
                {name : The name to use}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a command for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = 'Command';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var null
     */
    protected $dirKey = 'command';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'command';

    /**
     * 重载prepare
     *
     * @return boolean
     */
    public function prepare()
    {
        // 替换
        $this->replace([
            'command_signature' => $this->getSignature(),
        ]);

        return true;
    }

    /**
     * 获取 signature
     *
     * @return string
     */
    private function getSignature()
    {
        return $this->getModuleLowerName() . ':' . str_replace('_', '-', $this->getLowerNameInput());
    }
}
