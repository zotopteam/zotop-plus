<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use App\Modules\Maker\Lang;
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
     * @var null
     */
    protected $dirKey = 'controller';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'controller';
      
    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        $this->stub = $this->stub . '/' . $this->getTypeInput();

        if ($this->getModelInput()) {
            $this->stub = $this->stub . '/model';

            $this->replace([
                'input_model_basename' => $this->getModelBaseName(),
                'input_model_fullname' => $this->getModelFullName(),
                'input_model_list'     => $this->getModelList(),
                'input_model'          => $this->getModelInput(),
            ]);            
        } else {
            $this->stub = $this->stub . '/plain';
        }

        return true;
    }

    /**
     * 生成完成后执行
     * @return boolean
     */
    public function generated()
    {
        // 资源控制器带语言和view生成
        if ($this->getModelInput()) {

            $this->generateArrayLang($this->getNameInput(), [
                'title'  => $this->getNameInput(),
                'create' => trans('master.create'),
                'edit'   => trans('master.edit'),
                'show'   => trans('master.show'),
            ], $this->option('force'));

            foreach (['index', 'create', 'edit', 'show'] as $action) {
                $this->generateView($action, $this->option('force'));
            }

            return ;
        }

        $this->generateArrayLang($this->getNameInput(), [
            'title'  => $this->getNameInput(),
        ], $this->option('force'));        

        $this->generateView('index', $this->option('force'));
        return;
    }

    /**
     * 获取输入的 name
     * @return string
     */
    public function getTypeInput($key=null)
    {
        $type  = strtolower($this->option('type'));
        
        $types = $this->getConfigTypes($type);

        return $key ? Arr::get($types, $key) : $type;
    }    

    /**
     * 获取输入的 model
     * @return string
     */
    public function getModelInput()
    {
        return strtolower($this->option('model'));
    }

    /**
     * 获取类的命名空间
     * @return string
     */
    public function getClassNamespace($dirKey=null)
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
     * @return string
     */
    public function getFilePath()
    {
        $path = $this->getConfigDirs($this->dirKey);

        // 获取当前类型的目录
        $dir = $this->getTypeInput("dirs.controller");

        if ($dir) {
            $path = $path . DIRECTORY_SEPARATOR . Str::studly($dir);
        }

        return  $path . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    /**
     * 获取模型的基本类名
     * @return string
     */
    public function getModelBaseName()
    {
        return Str::studly($this->getModelInput());
    }

    /**
     * 获取模型的完整类名
     * @return string
     */
    public function getModelFullName()
    {
        return $this->getDirNamespace('model') . '\\' . $this->getModelBaseName();
    }

    /**
     * 获取模型的复数名词
     * @return string
     */    
    public function getModelList()
    {
        return Str::plural($this->getModelInput());
    }

    /**
     * 生成控制器对应动作的模板
     * @param  string $action 控制器动作名称 index,create,edit,show
     * @return void
     */
    public function generateView($action, $force=false)
    {
        $stub = $this->stub . '/' .$action;
        $path = $this->getConfigDirs('views').DIRECTORY_SEPARATOR. $this->getTypeInput("dirs.view");
        $path = $path . DIRECTORY_SEPARATOR.$this->getNameInput() . DIRECTORY_SEPARATOR . $action . '.blade.php';

        $this->generateStubFile($stub, $path, $force);

    }
}
