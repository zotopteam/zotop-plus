<?php

namespace App\Modules\Maker;

use App\Modules\NotFoundException;
use Illuminate\Support\Str;


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
            throw new NotFoundException("Module {$this->module} does not exist！");
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
        $this->name = strtolower($name);
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

        if(is_string($key) && !empty($key)) {
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
    public function getLangPath()
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
    protected function getLangContent()
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
        $data = collect($this->data);

        $newline = "\r\n";

        $content = $data->transform(function($value, $key) use($newline) {
            return "    '".addslashes($key)."' : '".addslashes($value)."',".$newline;
        })->implode('');
        
        return '{'.$newline.trim($content, ',').'}';
    }    

    /**
     * 保存
     * @param  boolean $force 是否覆盖已有文件
     * @return boolean
     */
    public function save($force=false)
    {
        $path = $this->getLangPath();

        if (!$force && $this->filesystem->exists($path)) {
            return false;
        }

        if (! $this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $this->filesystem->put($path, $this->getLangContent());
        return true;
    }

}
