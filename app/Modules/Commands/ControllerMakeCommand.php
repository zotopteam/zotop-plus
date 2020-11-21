<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-controller
                {module : The module to use}
                {name : The name to use}
                {--type=frontend : The type of controller,[backend|frontend|api].}
                {--model= : Create a resource controller from model.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = 'Controller';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var null
     */
    protected $dirKey = 'controller';

    /**
     * stub 用于从stubs中获取stub
     *
     * @var string
     */
    protected $stub = 'controller';

    /**
     * 重载prepare
     *
     * @return boolean
     */
    protected function prepare()
    {
        $this->stub = $this->stub . '/' . $this->getTypeInput();

        if ($this->getModelInput()) {

            $this->stub = $this->stub . '/model';

            $this->replace([
                'model_basename'        => $this->getModelBaseName(),
                'model_fullname'        => $this->getModelFullName(),
                'model_list'            => $this->getModelList(),
                'model_var'             => $this->getModelInput(),
                'controller_lower_name' => $this->getControllerLowerName(),
            ]);
        } else {
            $this->stub = $this->stub . '/plain';
        }

        return true;
    }

    /**
     * 生成完成后执行
     *
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    protected function generated()
    {
        $name = $this->getStudlyNameInput();

        // 资源view生成
        if ($this->getModelInput()) {

            $this->generateArrayLang($this->getLowerNameInput(), [
                'title'       => "[{$name} title]",
                'description' => "[{$name} description]",
                'create'      => "[Create {$name}]",
                'edit'        => "[Edit {$name}]",
                'show'        => "[Show {$name}]",
                'title.label' => "[Title label]",
                'title.help'  => "[Title help]",
            ], $this->option('force'));

            foreach (['index', 'create', 'edit', 'show'] as $action) {
                $this->generateView($action, $this->option('force'));
            }

            return;
        }

        $this->generateArrayLang($this->getLowerNameInput(), [
            'title'       => "[{$name} title]",
            'description' => "[{$name} description]",
        ], $this->option('force'));

        $this->generateView('index', $this->option('force'));
    }

    /**
     * 获取输入的 name
     *
     * @param string|null $key
     * @return string
     */
    protected function getTypeInput($key = null)
    {
        $type = strtolower($this->option('type'));

        $types = $this->getConfigTypes($type);

        return $key ? Arr::get($types, $key) : $type;
    }

    /**
     * 获取输入的 model
     *
     * @return string
     */
    protected function getModelInput()
    {
        return strtolower($this->option('model'));
    }

    /**
     * 获取类的命名空间
     *
     * @param string|null $dirKey
     * @return string
     */
    protected function getClassNamespace($dirKey = null)
    {
        $namespace = $this->getDirNamespace($this->dirKey);

        // 获取当前类型的目录
        $dir = $this->getTypeInput('dirs.controller');

        if ($dir) {
            return $namespace . '\\' . Str::studly($dir);
        }

        return $namespace;
    }

    /**
     * 获取文件相对路径，不含模块路径，如：Http/Controllers/Admin/Controller.php
     *
     * @return string
     */
    protected function getFilePath()
    {
        $path = $this->getConfigDirs($this->dirKey);

        // 获取当前类型的目录
        $dir = $this->getTypeInput("dirs.controller");

        if ($dir) {
            $path = $path . DIRECTORY_SEPARATOR . Str::studly($dir);
        }

        return $path . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    /**
     * 获取模型的基本类名
     *
     * @return string
     */
    protected function getModelBaseName()
    {
        return Str::studly($this->getModelInput());
    }

    /**
     * 获取模型的完整类名
     *
     * @return string
     */
    protected function getModelFullName()
    {
        return $this->getDirNamespace('model') . '\\' . $this->getModelBaseName();
    }

    /**
     * 获取模型的复数名词
     *
     * @return string
     */
    protected function getModelList()
    {
        return Str::plural($this->getModelInput());
    }

    /**
     * 获取控制器名称小写格式，不含Controller
     *
     * @return string
     */
    protected function getControllerLowerName()
    {
        return Str::replaceLast(strtolower($this->appendName), '', strtolower($this->getClassName()));
    }

    /**
     * 生成控制器对应动作的模板
     *
     * @param string $action 控制器动作名称 index,create,edit,show
     * @param bool $force
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    protected function generateView(string $action, $force = false)
    {
        $stub = $this->stub . '/' . $action;
        $path = $this->getConfigDirs('views') . DIRECTORY_SEPARATOR . $this->getTypeInput("dirs.view");
        $path = $path . DIRECTORY_SEPARATOR . $this->getLowerNameInput() . DIRECTORY_SEPARATOR . $action . '.blade.php';

        $this->generateStubFile($stub, $path, $force);
    }
}
