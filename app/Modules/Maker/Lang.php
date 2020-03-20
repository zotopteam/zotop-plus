<?php

namespace App\Modules\Maker;

use Illuminate\Support\Str;
use App\Modules\Exceptions\ModuleNotFoundException;


class Lang
{
    /**
     * 文件系统
     * @var FileSystem
     */
    protected $filesystem;

    /**
     * 配置
     * @var Config
     */
    protected $config;

    /**
     * 模块名称
     * @var string
     */
    protected $module;

    /**
     * 语言
     * @var string
     */
    protected $lang;

    /**
     * 数据
     * @var array
     */
    protected $data = [];

    /**
     * php类型短键语言使用的名称
     * @var string
     */
    protected $name;

    /**
     * 初始化
     * @param  string $module 模块名称
     * @param  string $lang   语言名称
     */
    public function __construct($module, $lang)
    {
        $this->filesystem = app('files');
        $this->config     = app('config');
        $this->module     = Str::Studly($module);
        $this->lang       = $lang;

        if (! $this->filesystem->exists($this->getModulePath('module.json'))) {
            throw new ModuleNotFoundException("Module {$this->module} does not exist！");
        }
    }

    /**
     * 实例化
     * @param  string $module 模块名称
     * @param  string $lang   语言名称
     * @return this
     */
    public static function instance($module, $lang)
    {
        return new static($module, $lang);
    }

     /**
     * 设置名称
     * @param  string $data
     * @return this
     */
    public function name($name)
    {
        $this->name = strtolower($this->filesystem->name($name));
        return $this;
    }  

    /**
     * 设置数据
     * @param  string|array $key
     * @param  mixed $value
     * @return this
     */
    public function data($key, $value=null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        }

        if(is_string($key)) {
            $this->data = array_merge($this->data, [$key=>$value]);
        }

        return $this;
    }

    /**
     * 获取模块路径
     * @param  string $subpath 
     * @return string
     */
    protected function getModulePath($subpath=null)
    {
        $path = $this->config->get('modules.paths.modules');
        $path = $path.DIRECTORY_SEPARATOR.$this->module;
        return $subpath? $path.DIRECTORY_SEPARATOR.$subpath : $path;
    }

    /**
     * 获取语言文件地址
     * @return string
     */
    public function getPath()
    {
        $path = $this->getModulePath($this->config->get('modules.paths.dirs.lang'));

        if ($this->name) {
            $path = $path.DIRECTORY_SEPARATOR.$this->lang.DIRECTORY_SEPARATOR.$this->name.'.php';
        } else {
            $path = $path.DIRECTORY_SEPARATOR.$this->lang.'.json';
        }

        return $path;       
    }

    /**
     * 获取语言文件内容
     * @return string
     */
    protected function convert()
    {
        if ($this->name) {
            return $this->convertToArrayString();
        }

        return $this->convertToJsonString();
    }

    /**
     * 转化数据为返回数组字符串 return [……]
     * @return string
     */
    protected function convertToArrayString()
    {
        $data = collect($this->data);

        // 获取键名最大长度
        $maxlength = $data->keys()->map(function($key) {
            return strlen($key);
        })->max();

        $newline = "\r\n";

        $content = $data->transform(function($value, $key) use($maxlength, $newline) {
            return "    ".str_pad("'".$key."'", $maxlength + 2, "  ")." => '".addslashes($value)."',".$newline;
        })->implode('');
        
        return '<?php'.$newline.'return ['.$newline.$content.'];';         
    }

    /**
     * 转化数据为Json字符串 {……}
     * @return string
     */
    protected function convertToJsonString()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    /**
     * 检查语言文件是否已经存在
     * @return bool
     */
    public function exists()
    {
        $path = $this->getPath();
        return $this->filesystem->isFile($path);
    }

    /**
     * 获取翻译数据
     * @param  string $key     键名
     * @param  string $default 默认值
     * @return mixed
     */
    public function get($key=null, $default=null)
    {
        $path = $this->getPath();

        if(! $this->filesystem->isFile($path)) {
            return [];
        }

        if ($this->filesystem->extension($path) == 'php') {
            $lang = $this->filesystem->getRequire($path);
        }

        if ($this->filesystem->extension($path) == 'json') {
            $lang = json_decode($this->filesystem->get($path), true);
        }

        $lang = is_array($lang) ? $lang : []; 

        if ($key) {
            return isset($lang[$key]) ? $lang[$key] : $default;
        }

        return $lang;
    }

    /**
     * 检查是否拥有键名
     * @param  string  $key 键名
     * @return boolean
     */
    public function has($key)
    {
        return collect($this->get())->has($key);
    }

    /**
     * 添加项
     * @param  mixed  $key 键名或者添加的数组
     * @param  mixed  $value 键值 
     * @return boolean
     */
    public function set($key, $value='')
    {
        $this->data = $this->get();

        if (is_string($key)) {
            $this->data = array_merge($this->data, [$key=>$value]);
        }

        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        }

        return $this->save(true);
    }

    /**
     * 添加项
     * @param  mixed  $key 键名或者添加的数组
     * @return boolean
     */
    public function forget($key)
    {
        $this->data = $this->get();

        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
        
        return $this->save(true);
    }

    /**
     * 保存
     * @param  boolean $force 是否覆盖已有文件
     * @return boolean
     */
    public function save($force=false)
    {
        $path = $this->getPath();

        if (!$force && $this->filesystem->exists($path)) {
            return false;
        }

        if (! $this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $this->filesystem->put($path, $this->convert());
        return true;
    }

    /**
     * 删除语言文件
     * @return boolean
     */
    public function delete()
    {
        return $this->filesystem->delete($this->getPath());
    }

}
