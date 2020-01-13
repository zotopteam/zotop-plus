<?php

namespace App\Modules\Maker;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

abstract class GeneratorCommand extends Command
{
    use GeneratorTrait;

    /**
     * 前面追加的名称
     * 
     */
    protected $prependName = null;

    /**
     * 后面追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = null;

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$pathDirKey}“)
     * @var null
     */
    protected $pathDirKey = null;

    /**
     * 文件扩展名
     * @var string
     */
    protected $extension = 'php';

    /**
     * stub 用于从stubs中获取stub
     * 
     * @var string
     */
    protected $stub = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 检查模块是否存在
        if (! $this->hasModule()) {
            $this->error('Module '.$this->getModuleStudlyName().' does not exist!');
            return;
        }

        if ($this->prepare()) {
            $this->generate();
        }        
    }

    /**
     * 生成前的预备函数，可以用来设置替换数据和各项参数
     * @return boolean
     */
    public function prepare()
    {
        $this->replace('class_name', $this->getClassName());
        return true;     
    }

    /**
     * 生成文件
     * @return void
     */
    public function generate()
    {
        $this->generateStubFile($this->stub, $this->getFilePath(), $this->option('force'));
    }

    /**
     * 获取 name
     * @return string
     */
    public function getArgumentName()
    {
        return $this->argument('name');
    }

    /**
     * 获取类名称
     * @return string
     */
    Public function getClassName()
    {
        $className = Str::studly($this->getArgumentName());

        //前面追加的名称
        if ($this->prependName) {
            $className = Str::start($className, $this->prependName);            
        }

        // 部分类以特殊标识结尾，补充结尾标识，如：CoreServiceProvider, AdminRequest
        if ($this->appendName) {
            $className = Str::finish($className, $this->appendName);
        }

        return $className;
    }

    /**
     * 获取文件名称，默认文件名和类名一致 如：TestCommand.php
     * @return string
     */
    public function getFileName()
    {
        return $this->getClassName().'.'.$this->extension;
    }

    /**
     * 获取文件相对路径，不含模块路径，如：Http/Controllers
     * @return string
     */
    public function getFilePath()
    {
        $path = $this->laravel['config']->get("modules.paths.dirs.{$this->pathDirKey}");
        $path = $path.DIRECTORY_SEPARATOR.$this->getFileName();

        return $path;
    }
}
