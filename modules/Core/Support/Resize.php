<?php
namespace Modules\Core\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;
use BadMethodCallException;

class Resize
{
    /**
     * 图片实例
     * 
     * @var Image
     */
    protected $image;

    /**
     * 文件实例
     * 
     * @var Image
     */
    protected $files;

    /**
     * 设置
     * @var array
     */
    protected $config = [];


    /**
     * init the resize
     * 
     * @param Image $image  [description]
     * @param array $config [description]
     */
    public function __construct()
    {
        $this->image  = app('image');
        $this->files  = app('files');
        $this->config = config('core.image.resize');
    }

    /**
     * 自定义参数，可以链式使用
     * with([……]) //覆盖系统设置
     * with(false) //关闭resize
     * with('width','1000') //使用自定义宽度
     * with('width','auto') //使用自动宽度
     * @param  string|array $key  键名或者配置数组
     * @param  mixed $value 键值
     * @return this
     */
    public function with($key, $value=null)
    {
        //设置key值
        if (is_string($key)) {

            // 关闭
            if ($key === false || $key === 'false') {
                $this->config['enabled'] = false;
            }

            // 赋值
            if (! is_null($value)) {
                $this->config[$key] = $value;
            }
        }

        // 群组操作
        if (is_array($key)) {
            $this->config = array_merge($this->config, $key);
        }

        return $this;
    }

    /**
     * 打开图片->缩放->保存
     * 
     * @param  string $image 图片路径
     * @param  string $target 保存路径，如果未设置，直接覆盖原图 
     * @return \Intervention\Image\Image
     */
    public function apply($source, $target=null)
    {
        $image  = $this->image->make($source);
        $config = $this->config($image);
        $target = $target ?? $source;
        
        if ($config['enabled'] && ($image->width() > $config['width'] || $image->height() > $config['height'])) {
            
            // 如果目录不存在，尝试创建
            if (! $this->files->isDirectory($dir = dirname($target)) ) {
                $this->files->makeDirectory($dir, 0775, true);
            }

            // 缩放并保持比例
            $image->resize($config['width'], $config['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();                
            });

            return $image->save($target, $config['quality']);
        }

        return null;
    }

    /**
     * 修正参数相关数据
     * 
     * @param  Image $image  源图片实例
     * @return array
     */
    public function config($image)
    {
        $config = $this->config;

        // 宽高
        $config['width']    = intval($config['width']) ?: null;
        $config['height']   = intval($config['height']) ?: null;

        // 质量
        $config['quality']  = min(intval($config['quality']), 100);

        return $config;
    }

    /**
     * Dynamically bind parameters to the view.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Modules\Core\Support\Watermark
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! Str::startsWith($method, 'with')) {
            throw new BadMethodCallException("Method [$method] does not exist on view.");
        }

        return $this->with(Str::camel(substr($method, 4)), $parameters[0]);
    }

    /**
     * Handle dynamic static method calls into the method.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }         
}
