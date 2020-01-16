<?php

namespace App\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Modules\Maker\GeneratorCommand;

class PolicyMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-policy
                {module : The module to use}
                {name : The name to use}
                {--model= : The model that the policy applies to.}
                {--force : Force the operation to run when it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy for the specified module.'; 

    /**
     * 追加的名称，比如名称后面追加 Request,ServiceProvider
     * 
     */
    protected $appendName = '';

    /**
     * 目标路径键名，用于从config中获取对应路径 config(”modules.paths.dirs.{$dirKey}“)
     * @var null
     */
    protected $dirKey = 'policies';

    /**
     * stub 用于从stubs中获取stub
     * @var string
     */
    protected $stub = 'policy';

    /**
     * 重载prepare
     * @return boolean
     */
    public function prepare()
    {
        if ($this->option('model')) {
            $this->stub = 'policy_model';

            $this->replace([
                'input_model_fullname' => $this->getInputModelFullName(),
                'input_model_basename' => $this->getInputModelBaseName(),
                'input_model'          => strtolower($this->getInputModelBaseName()),
            ]);
        }

        $this->replace([
            'user_model_fullname' => $this->getUserModelFullName(),
            'user_model_basename' => $this->getUserModelBaseName(),
        ]);

        return true;
    }

    /**
     * 获取auth中配置的用户类全名，含命名空间
     * 
     * @return string
     */
    public function getUserModelFullName()
    {
        $guard    = $this->laravel['config']->get('auth.defaults.guard');
        $provider = $this->laravel['config']->get("auth.guards.{$guard}.provider");

        return $this->laravel['config']->get("auth.providers.{$provider}.model");
    }

    /**
     * 获取auth中配置的用户类名，不含命名空间
     * 
     * @return string
     */
    public function getUserModelBaseName()
    {
        return class_basename($this->getUserModelFullName());
    }

    /**
     * 获取输入类的类基本名称，不带命名空间
     * @return string
     */
    protected function getInputModelBaseName()
    {
        return Str::studly($this->option('model'));
    }

    /**
     * 获取输入类的全名，带命名空间
     * @return string
     */
    protected function getInputModelFullName()
    {
        return $this->getDirNamespace('model') . '\\' .$this->getInputModelBaseName();
    }      

}
