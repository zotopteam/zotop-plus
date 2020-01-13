<?php
namespace App\Modules\Maker;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;

trait GeneratorTrait
{
    protected $replaces = [];

    /**
     * 检查模块是否存在
     * @return boolean
     */
    public function hasModule()
    {
        if (file_exists($this->getModulePath('module.json'))) {
            return true;
        }

        return false;
    }

    /**
     * 获取模块名称
     * @return string
     */
    public function getModuleName()
    {
        return 'test';
        return $this->argument('module');
    }

    /**
     * 获取模块小写名称
     * @return string
     */
    public function getModuleLowerName($value='')
    {
        return strtolower($this->getModuleName());
    }

    /**
     * 获取模块变种驼峰名称 foo_bar => FooBar
     * @return string
     */
    public function getModuleStudlyName()
    {
        return Str::studly($this->getModuleName());
    }
    /**
     * 获取模块蛇式名称  FooBar => foo_bar
     * @return string
     */
    public function getModuleSnakeName()
    {
        return Str::snake($this->getModuleName());
    }

    /**
     * 获取模块命名空间
     * @return string
     */
    public function getModuleNameSpace()
    {
        return 'Modules';
    }

    /**
     * 获取模块路径
     * @param  string  $subpath 子路径，或者子路径key
     * @param  boolean $isPath  是否为路径
     * @return string
     */
    public function getModulePath($subpath=null, $isPath=true)
    {
        $path = $this->laravel['config']->get('modules.paths.modules');
        $path = $path.DIRECTORY_SEPARATOR.$this->getModuleStudlyName();

        if (empty($subpath)) {
            return $path;
        }

        if ($isPath) {
            return $path.DIRECTORY_SEPARATOR.$subpath;;
        }
        
        return $path.DIRECTORY_SEPARATOR.$this->laravel['config']->get("modules.paths.{$subpath}");
    }

    /**
     * 获取stub路径
     * @param  string $stub      stub name
     * @param  string $extension extention
     * @return string
     */
    public function getStubPath($stub)
    {
        //如果不包含扩展名，则扩展名为stub
        if (! Str::contains($stub, '.')) {
            $stub = $stub.'.stub';
        }

        return $this->stub = __DIR__.'/stubs/'.$stub;
    }

    /**
     * 获取模块内容
     * @param  string $stub stub name
     * @return string       
     */
    public function getStubContent($stub)
    {
        $path = $this->getStubPath($stub);

        return $this->laravel['files']->get($path);
    }

    /**
     * stub 替换
     * 
     * @param  string|array|null $key  为空时返回替换数组，其余为设置值
     * @param  string|null $value 
     * @return string        
     */
    public function replace($key=null, $value=null)
    {
        if (empty($this->replaces)) {

            //模块信息替换 
            $this->replaces = [
                'studly_name'     => $this->getModuleStudlyName(),
                'lower_name'      => $this->getModuleLowerName(),
                'snake_name'      => $this->getModuleSnakeName(),
                'namespace'       => $this->getModuleNameSpace(),
                'vendor'          => $this->laravel['config']->get('modules.composer.vendor'),
                'author_name'     => $this->laravel['config']->get('modules.composer.author.name'),
                'author_email'    => $this->laravel['config']->get('modules.composer.author.email'),
                'author_homepage' => $this->laravel['config']->get('modules.composer.author.homepage'),
            ];

            //命名空间替换
            foreach ($this->laravel['config']->get('modules.paths.dirs') as $name => $path) {
                $this->replaces[$name.'_namespace'] = str_replace('/', '\\', $path);
            }

        }

        if (empty($key)) {
            return $this->replaces;
        }

        if (is_array($key)) {
            $this->replaces = array_merge($this->replaces, $key);
        }

        if (is_string($key)) {
            $this->replaces = array_merge($this->replaces, [$key => $value]);
        }

        return $this;
    }

    /**
     * 渲染stub
     * @param  string $stub 
     * @return string       
     */
    public function renderStub($stub)
    {
        $content  = $this->getStubContent($stub);

        foreach ($this->replace() as $search => $replace) {
            $content = str_replace('$' . strtoupper($search) . '$', $replace, $content);
        }        

        return $content;
    }

    /**
     * 生成文件
     * @param  string $stub 
     * @param  string $path 
     * @return string       
     */
    public function generateStubFile($stub, $path, $force=false)
    {
        $path = $this->getModulePath($path);

        if (! $force && $this->laravel['files']->exists($path)) {
            $this->error('Existed: '. $path);
            return;
        }

        if (! $this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0775, true);
        }

        $content = $this->renderStub($stub);

        // 替换json文件中的斜线为双斜线
        if (Str::endsWith($path, '.json')) {
            $content = str_replace('\\', '\\\\', $content);
        }

        $this->laravel['files']->put($path, $content);

        $this->info('Created: '.$path);
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function generateGitKeep($path)
    {
        $this->laravel['files']->put($path . DIRECTORY_SEPARATOR .'.gitkeep', '');
    }        
}
