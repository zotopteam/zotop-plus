<?php

namespace App\Modules\Maker;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends Command
{
    use GeneratorTrait;

    /**
     * Reserved names that cannot be used for generation.
     *
     * @var string[]
     */
    protected $reservedNames = [
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'fn',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
    ];

    /**
     * 后面追加的名称，比如名称后面追加 Request,ServiceProvider
     *
     */
    protected $appendName = null;

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     *
     * @var null
     */
    protected $dirKey = null;

    /**
     * 文件扩展名
     *
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
     * @return bool|null
     * @throws \App\Modules\Exceptions\FileExistedException
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function handle()
    {
        // 检查模块是否存在
        if (!$this->hasModule()) {
            $this->error('Module ' . $this->getModuleStudlyName() . ' does not exist!');
            return false;
        }

        // 验证名称是否为保留
        if ($this->hasArgument('name') && $this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');
            return false;
        }

        // 全局替换
        $this->replace([
            'class_name'      => $this->getClassName(),
            'class_namespace' => $this->getClassNamespace(),
            'lower_name'      => $this->getLowerNameInput(),
            'studly_name'     => $this->getStudlyNameInput(),
        ]);

        if ($this->prepare()) {
            $this->generate();
        }
    }

    /**
     * 生成前的预备函数，可以用来设置替换数据和各项参数
     *
     * @return boolean
     */
    protected function prepare()
    {
        return true;
    }

    /**
     * 生成文件
     *
     * @return void
     * @throws \App\Modules\Exceptions\FileExistedException
     */
    protected function generate()
    {
        if ($this->generateStubFile($this->stub, $this->getFilePath(), $this->option('force'))) {
            $this->generated();
        }
    }

    /**
     * 生成后扩展
     *
     * @return boolean
     */
    protected function generated()
    {
        return true;
    }

    /**
     * 获取类的命名空间
     *
     * @return string
     */
    protected function getClassNamespace()
    {
        return $this->getDirNamespace($this->dirKey);
    }

    /**
     * 获取输入的 name
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * 获取输入的 name 的小写格式
     *
     * @return string
     */
    protected function getLowerNameInput()
    {
        return strtolower($this->getNameInput());
    }

    /**
     * 获取输入的 name 的变种驼峰命名 foo_bar => FooBar
     *
     * @return string
     */
    protected function getStudlyNameInput()
    {
        return Str::studly($this->getNameInput());
    }

    /**
     * 获取类名称
     *
     * @return string
     */
    protected function getClassName()
    {
        $className = $this->getStudlyNameInput();

        // 部分类以特殊标识结尾，补充结尾标识，如：CoreServiceProvider, AdminRequest
        if ($this->appendName) {
            $className = Str::finish($className, $this->appendName);
        }

        return $className;
    }

    /**
     * 获取类的全名，带命名空间
     *
     * @return string
     */
    protected function getClassFullName()
    {
        return $this->getClassNamespace() . '\\' . $this->getClassName();
    }

    /**
     * 获取文件名称，默认文件名和类名一致 如：TestCommand.php
     *
     * @return string
     */
    protected function getFileName()
    {
        return $this->getClassName() . '.' . $this->extension;
    }

    /**
     * 获取文件相对路径，不含模块路径，如：Http/Controllers/Controller.php
     *
     * @return string
     */
    protected function getFilePath()
    {
        return $this->getConfigDirs($this->dirKey) . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    /**
     * 检查给定名称是否保留
     *
     * @param string $name
     * @return bool
     */
    protected function isReservedName($name)
    {
        return in_array(strtolower($name), $this->reservedNames);
    }
}
