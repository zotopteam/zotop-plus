<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;

class ControlMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-control
                {module : The module to use}
                {name : The name to use}
                {--view=* : The view of control, [backend|frontend|api].}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form control for the specified module.';

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
    protected $dirKey = 'controls';

    /**
     * 重载prepare
     *
     * @return boolean
     */
    public function prepare()
    {
        // 默认使用内联组件视图
        $this->stub = 'control/inline.stub';

        // 如果有view参数，将创建独立视图
        if ($this->getViewInput()) {
            $this->stub = 'control/view.stub';;
        }

        return true;
    }

    /**
     * 如果有视图参数，生成完成后创建视图
     *
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    public function generated()
    {
        foreach ($this->getViewInput() as $view) {
            $this->generateView($view, $this->option('force'));
        }

        $this->info('Insert code in the boot of ' . $this->getModuleName() . ' ServiceProvider: ');
        $this->info("Form::control('" . $this->getLowerNameInput() . "', " . $this->getClassName() . "::class);");
    }

    /**
     * 创建模板
     *
     * @param string $type 类型：backend,fronted,api
     * @param boolean $force 强制生成
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    public function generateView(string $type, $force = false)
    {
        $stub = 'control/view-blade.stub';
        $path = $this->getConfigDirs('views') . DIRECTORY_SEPARATOR . $this->getConfigTypes("{$type}.dirs.view");
        $path = $path . DIRECTORY_SEPARATOR . 'controls' . DIRECTORY_SEPARATOR . $this->getLowerNameInput() . '.blade.php';

        $this->generateStubFile($stub, $path, $force);
    }

    /**
     * 获取输入的 view
     *
     * @return array
     */
    public function getViewInput()
    {
        $view = $this->option('view');

        // 过滤掉不允许的值
        return array_filter((array)$view, function ($item) {
            return in_array($item, ['backend', 'frontend', 'api']);
        });
    }
}
