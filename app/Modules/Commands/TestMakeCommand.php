<?php

namespace App\Modules\Commands;

use App\Modules\Maker\GeneratorCommand;
use Illuminate\Support\Str;

class TestMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-test
                {module : The module to use}
                {name : The name to use}
                {--type=feature :  Create a feature or unit test, allow: feature|unit}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new feature or unit test class for the specified module.';

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = '';

    /**
     * 目标路径键名
     *
     * @var null
     */
    protected $dirKey = 'test';

    /**
     * stub
     *
     * @var string
     */
    protected $stub = 'test';

    /**
     * 生成前准备
     *
     * @return boolean
     */
    public function prepare()
    {
        $this->stub = 'test/' . $this->getTypeInput();

        return true;
    }

    /**
     * 获取输入的类型，feature=功能测试 unit=单元测试
     *
     * @return string
     * @author Chen Lei
     * @date 2020-11-07
     */
    protected function getTypeInput()
    {
        $type = strtolower($this->option('type'));

        if (!in_array($type, ['feature', 'unit'])) {
            $type = 'feature';
        }

        return $type;
    }


    /**
     * 获取类的命名空间
     *
     * @param string|null $dirKey
     * @return string
     */
    public function getClassNamespace($dirKey = null)
    {
        $namespace = $this->getDirNamespace($this->dirKey);

        // 获取当前类型的目录
        $dir = $this->getTypeInput();

        return $namespace . '\\' . Str::studly($dir);
    }

    /**
     * 获取文件相对路径，不含模块路径
     *
     * @return string
     */
    public function getFilePath($dirKey = null)
    {
        $path = $this->getConfigDirs($dirKey ?? $this->dirKey);

        // 获取当前类型的目录
        $dir = $this->getTypeInput();

        return $path . DIRECTORY_SEPARATOR . Str::studly($dir) . DIRECTORY_SEPARATOR . $this->getFileName();
    }
}
