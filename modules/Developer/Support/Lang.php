<?php
namespace Modules\Developer\Support;

class Lang
{
    private $filesystem;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->filesystem = app('files');
    }

    /**
     * 检查是否存在
     * 
     * @param  string $file    文件
     * @return mied
     */    
    public function exists($file)
    {
        return $this->filesystem->exists($file);
    }

    /**
     * 删除文件
     * 
     * @param  string $file    文件
     * @return mied
     */
    public function delete($file)
    {
        return $this->filesystem->delete($file);
    }    

    /**
     * 获取数据
     * @param  string $file    文件
     * @param  string $key     键名
     * @param  string $default 默认值
     * @return mied
     */
    public function get($file, $key=null, $default=null)
    {
        $langs = [];

        if ($this->exists($file)) {
            $langs = include($file);
            $langs = is_array($langs) ? $langs : [];
        }

        if ($key) {
            return isset($langs[$key]) ? $langs[$key] : $default;
        }

        return collect($langs);
    }

    /**
     * 设置数据
     * 
     * @param  string $file    文件
     * @param  string $data     键名
     * @param  string $filter 是否过滤空值
     * @return mied
     */
    public function set($file, array $data, $filter=false)
    {
        // 合并并过滤空值
        $langs = collect($data)->filter(function($value, $key) use($filter) {
            if ($filter && empty($value)) {
                return false;
            }
            return true;
        });

        $content = $this->convert($langs);

        if (! $this->filesystem->isDirectory($dir = dirname($file))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }
        
        $this->filesystem->put($file, $content);

        return true;
    }

    /**
     * 删除键值
     * 
     * @param  string $file 文件
     * @param  string $key  键名
     * @return bool
     */
    public function forget($file, $key)
    {
        $langs = $this->get($file)->forget($key);

        $content = $this->convert($langs);

        $this->filesystem->put($file, $content);

        return true;
    }

    /**
     * 将语言集合转化为数组字符串
     * 
     * @param  collect $langs
     * @return string
     */
    public function convert($langs)
    {
        // 获取键名最大长度
        $maxlength = $langs->keys()->map(function($key) {
            return strlen($key);
        })->max();

        $newline = "\r\n";

        $content = $langs->transform(function($value, $key) use($maxlength, $newline) {
            return "    ".str_pad("'".$key."'", $maxlength + 2, "  ")." => '".$value."',".$newline;
        })->implode('');

        $content = '<?php'.$newline.'return ['.$newline.$content.'];';  
        
        return $content;      
    }


    /**
     * 处理语言文件名称，默认附加.php，如果名称错误，返回null
     * 
     * @param  string $name
     * @return mixed
     */
    public function fileName($name)
    {
        $name = trim(strtolower($name), '.php');

        if (! preg_match("/^[a-z0-9]+$/", $name)) {
            return null;
        }

        return $name.'.php';
    }

    /**
     * 处理key名称，如果名称错误，返回null
     * 
     * @param  string $name
     * @return mixed
     */
    public function keyName($key)
    {
        $key = strtolower($key);
        $key = trim($key, '.');

        if (! preg_match("/^[a-z0-9.]+$/", $key)) {
            return null;
        }

        return $key;
    }
}
