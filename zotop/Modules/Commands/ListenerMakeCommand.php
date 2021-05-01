<?php

namespace Zotop\Modules\Commands;

use Zotop\Modules\Maker\GeneratorCommand;
use Illuminate\Support\Str;

class ListenerMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-listener
                {module : The module to use}
                {name : The name to use}
                {--event= : The event class being listened for.}
                {--queued : Indicates the event listener should be queued.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new listener for the specified module.';

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
    protected $dirKey = 'listener';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'listener';

    /**
     * 重载prepare
     *
     * @return boolean
     */
    public function prepare()
    {
        $this->stub = $this->option('queued') ? 'listener-queued-duck' : 'listener-duck';

        if ($this->option('event')) {

            $this->stub = $this->option('queued') ? 'listener-queued' : 'listener';

            $this->replace([
                'event_name'      => $this->getEventName(),
                'event_full_name' => $this->getEventFullName(),
            ]);
        }

        return true;
    }

    /**
     * 获取事件的类名称
     *
     * @return string
     */
    protected function getEventName()
    {
        return Str::studly($this->option('event'));
    }

    /**
     * 获取事件的类名称，带命名空间
     *
     * @return string
     */
    protected function getEventFullName()
    {
        return $this->getDirNamespace('events') . '\\' . $this->getEventName();
    }
}
