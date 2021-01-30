<?php

namespace App\Modules\Maker;

use App\Modules\Exceptions\FileExistedException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait GeneratorTrait
{
    /**
     * 替换数组
     *
     * @var array
     */
    protected $replaces = [];

    /**
     * 获取模块全局配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfig($key = null)
    {
        if ($key) {
            $value = $this->laravel['config']->get("modules.{$key}");

            if (!isset($value)) {
                throw new \Exception("config {$key} does not exist", 1);
            }

            return $value;
        }

        return $this->laravel['config']->get('modules');
    }

    /**
     * 获取模块全局路径配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfigPaths($key = null)
    {
        return $key ? $this->getConfig("paths.{$key}") : $this->getConfig('paths');
    }

    /**
     * 获取模块全局目录配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfigDirs($key = null)
    {
        return $key ? $this->getConfigPaths("dirs.{$key}") : $this->getConfigPaths('dirs');
    }

    /**
     * 获取模块全局目录配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfigFiles($key = null)
    {
        return $key ? $this->getConfigPaths("files.{$key}") : $this->getConfigPaths('files');
    }

    /**
     * 获取模块全局类型配置
     *
     * @param string|null $key
     * @return array
     * @throws \Exception
     */
    public function getConfigTypes($key = null)
    {
        return $key ? $this->getConfig("types.{$key}") : $this->getConfig('types');
    }

    /**
     * 获取模块全局的命名空间
     *
     * @return string
     */
    public function getNamespace()
    {
        return 'Modules';
    }


    /**
     * 检查模块是否存在
     *
     * @return boolean
     * @throws \Exception
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
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->argument('module');
    }

    /**
     * 获取模块小写名称
     *
     * @return string
     */
    public function getModuleLowerName()
    {
        return strtolower($this->getModuleName());
    }

    /**
     * 获取模块变种驼峰名称 foo_bar => FooBar
     *
     * @return string
     */
    public function getModuleStudlyName()
    {
        return Str::studly($this->getModuleName());
    }

    /**
     * 获取模块蛇式名称  FooBar => foo_bar
     *
     * @return string
     */
    public function getModuleSnakeName()
    {
        return Str::snake($this->getModuleName());
    }

    /**
     * 获取模块命名空间
     *
     * @return string
     */
    public function getModuleNamespace()
    {
        return $this->getNamespace() . '\\' . $this->getModuleStudlyName();
    }

    /**
     * 获取模块路径
     *
     * @param string|null $subpath 子路径，或者子路径key
     * @param boolean $isPath 是否为路径
     * @return string
     * @throws \Exception
     */
    public function getModulePath($subpath = null, $isPath = true)
    {
        $path = $this->getConfigPaths('modules') . DIRECTORY_SEPARATOR . $this->getModuleStudlyName();

        if (empty($subpath)) {
            return $path;
        }

        if ($isPath) {
            return $path . DIRECTORY_SEPARATOR . $subpath;
        }

        return $path . DIRECTORY_SEPARATOR . $this->getConfigPaths($subpath);
    }

    /**
     * 获取目录的命名空间
     *
     * @param string $dirKey
     * @return string
     * @throws \Exception
     */
    public function getDirNamespace(string $dirKey)
    {
        return $this->getModuleNamespace() . '\\' . str_replace('/', '\\', $this->getConfigDirs($dirKey));
    }

    /**
     * 获取stub路径
     *
     * @param string|null $stub stub name
     * @return string
     */
    public function getStubPath($stub = null)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Stubs';

        if ($stub) {

            //如果不包含扩展名，则扩展名为stub
            if (!Str::contains($stub, '.')) {
                $stub = $stub . '.stub';
            }

            return $path . DIRECTORY_SEPARATOR . $stub;
        }

        return $path;
    }

    /**
     * 获取模块内容
     *
     * @param string|null $stub stub name
     * @return string
     */
    public function getStubContent(?string $stub)
    {
        $path = $this->getStubPath($stub);

        if (!$this->laravel['files']->exists($path)) {
            $this->warn('Unknown: ' . $path);
            return null;
        }

        return $this->laravel['files']->get($path);
    }

    /**
     * stub 替换
     *
     * @param string|array|null $key 为空时返回替换数组，其余为设置值
     * @param string|null $value
     * @return mixed
     * @throws \Exception
     */
    public function replace($key = null, $value = null)
    {
        if (empty($this->replaces)) {

            //模块信息替换
            $this->replaces = [
                'namespace'          => $this->getNamespace(),
                'module_studly_name' => $this->getModuleStudlyName(),
                'module_lower_name'  => $this->getModuleLowerName(),
                'module_snake_name'  => $this->getModuleSnakeName(),
                'module_namespace'   => $this->getModuleNamespace(),
                'vendor'             => $this->getConfig('composer.vendor'),
                'author_name'        => $this->getConfig('composer.author.name'),
                'author_email'       => $this->getConfig('composer.author.email'),
                'author_homepage'    => $this->getConfig('composer.author.homepage'),
            ];

            //命名空间替换
            foreach ($this->getConfigDirs() as $name => $path) {
                $this->replaces[$name . '_namespace'] = str_replace('/', '\\', $path);
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
     *
     * @param string $stub
     * @return string
     * @throws \Exception
     */
    public function renderStub(string $stub)
    {
        $content = $this->getStubContent($stub);

        foreach ($this->replace() as $search => $replace) {
            $content = str_replace('$' . strtoupper($search) . '$', $replace, $content);
        }

        return $content;
    }

    /**
     * 生成文件
     *
     * @param string $stub 不含文件后缀则自动补充.stub
     * @param string $path 相对模块的路径
     * @param bool $force
     * @return string
     * @throws \Exception
     */
    public function generateStubFile(string $stub, string $path, $force = false)
    {
        $path = $this->getModulePath($path);

        if (!$force && $this->laravel['files']->exists($path)) {

            if ($this->laravel->runningInConsole()) {
                $this->warn('Existed: ' . $path);
                return false;
            }

            throw new FileExistedException('Existed: ' . $path, 1);
        }

        if ($content = $this->renderStub($stub)) {

            // 自动创建不存在的目录
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0775, true);
            }

            // 替换json文件中的斜线为双斜线
            if (Str::endsWith($path, '.json')) {
                $content = str_replace('\\', '\\\\', $content);
            }

            $this->laravel['files']->put($path, $content);

            $this->info('Created: ' . $path);
            return true;
        }

        return false;
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function generateGitKeep(string $path)
    {
        $this->laravel['files']->put($path . DIRECTORY_SEPARATOR . '.gitkeep', 'git keep');
    }

    /**
     * 创建短键语言文件
     *
     * @param string $name 文件名称
     * @param array $data 数据
     * @param bool $force 是否覆盖
     * @return void
     */
    public function generateArrayLang(string $name, $data = [], $force = false)
    {
        $lang = $this->laravel['config']->get('app.locale');
        $lang = Lang::instance($this->getModuleStudlyName(), $lang);

        if ($lang->name($name)->data($data)->save($force)) {
            $this->info('Created: ' . $lang->getPath());
            return;
        }

        $this->warn('Existed: ' . $lang->getPath());
    }

    /**
     * 创建文本翻译文件
     *
     * @param array $data 数据
     * @param bool $force 是否覆盖
     * @return void
     */
    public function generateJsonLang($data = [], $force = false)
    {
        $lang = $this->laravel['config']->get('app.locale');
        $lang = Lang::instance($this->getModuleStudlyName(), $lang);

        if ($lang->data($data)->save($force)) {
            $this->info('Created: ' . $lang->getPath());
            return;
        }

        $this->warn('Existed: ' . $lang->getPath());
    }

    /**
     * 创建目录
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path)
    {
        if (!$this->laravel['files']->isDirectory(dirname($path))) {
            $this->laravel['files']->makeDirectory(dirname($path), 0777, true, true);
            return true;
        }

        return false;
    }

    /**
     * 删除文件
     *
     * @param array|string $files
     * @return void
     */
    protected function deleteFiles($files)
    {
        $files = Arr::wrap($files);

        // 删除文件
        //array_map([$this->laravel['files'], 'delete'], $files);
        foreach ($files as $file) {
            $this->laravel['files']->delete($file);
            $this->warn('Deleted: ' . $file);
        }
    }
}
