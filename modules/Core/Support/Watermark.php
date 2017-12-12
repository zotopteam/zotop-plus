<?php
namespace Modules\Core\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;
use BadMethodCallException;

class Watermark
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
     * 水印设置
     * @var array
     */
    protected $config = [];

    /**
     * 文字和图片位置统一
     * @var array
     */
    protected $positions = [
            'top-left'     => 'top-left',
            'top'          => 'top-center',
            'top-right'    => 'top-right',
            'left'         => 'middle-left',
            'center'       => 'middle-center',
            'right'        => 'middle-right',
            'bottom-left'  => 'bottom-left',
            'bottom'       => 'bottom-center',
            'bottom-right' => 'bottom-right',
    ];

    /**
     * init the watermark
     * 
     * @param Image $image  [description]
     * @param array $config [description]
     */
    public function __construct()
    {
        $this->image  = app('image');
        $this->files  = app('files');
        $this->config = config('core.image.watermark');
    }

    /**
     * 自定义参数，可以链式使用
     * with([……]) //覆盖系统设置
     * with(false) //关闭水印
     * with('image') //使用图片水印，水印图片为系统设置图片
     * with('text') //使用文字水印，水印文字为系统设置文字
     * with('image','path of image') //使用自定义图片水印
     * with('text','text string') //使用自定义文字水印
     * with('postion','bottom-right') //自定义水印位置
     * with('font.size',24) //自定义文字大小
     * with('font.color','#ffffff') //自定义文字颜色
     * 
     * @param  string|array $key  键名或者配置数组
     * @param  mixed $value 键值
     * @return this
     */
    public function with($key, $value=null)
    {

        //设置key值
        if (is_string($key)) {

            // 当传入的key为image或者text时，设置水印类型
            if (in_array($key, ['image','text'])) {
                $this->config['type'] = $key;
            }

            // 关闭
            if ($key === false || $key === 'false') {
                $this->config['enabled'] = false;
            }

            // 赋值
            if (! is_null($value)) {
                $this->config = array_set($this->config, $key, $value);
            }
        }

        // 群组操作
        if (is_array($key)) {
            $this->config = array_merge_deep($this->config, $key);
        }

        return $this;
    }

    /**
     * 打开图片->添加水印->保存
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
        
        if ($config['enabled'] && $image->width() > $config['width'] && $image->height() > $config['height']) {
            
            // 如果目录不存在，尝试创建
            if (! $this->files->isDirectory($dir = dirname($target)) ) {
                $this->files->makeDirectory($dir, 0775, true);
            }

            // 图片水印
            if ($config['type'] == 'image' && $watermark = $config['image']) {
                // 水印图片实例
                $watermark = $this->image->make($watermark);
                // 水印透明度
                $watermark->opacity($config['opacity']);
                // 插入水印
                $image->insert($watermark, $config['position'], $config['offset']['x'], $config['offset']['y']);     
            }

            // 文字水印
            if ($config['type'] == 'text' && $text = $config['text']) {
                $image->text($text, $config['offset']['x'], $config['offset']['y'], function ($font) use($config) {
                    $font->file($config['font']['file']);
                    $font->size($config['font']['size']);
                    $font->color($config['font']['color']);
                    $font->align($config['font']['align']);  //left, right or center. 
                    $font->valign($config['font']['valign']); //top, bottom or middle.
                }); 
            }

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
        $config['width']    = intval($config['width']);
        $config['height']   = intval($config['height']);

        // 透明度和质量
        $config['opacity']  = min(intval($config['opacity']), 100);
        $config['quality']  = min(intval($config['quality']), 100);

        // 位置
        $config['position'] =  in_array(strtolower($config['position']), array_keys($this->positions)) 
            ? strtolower($config['position'])
            : 'bottom-right';

        // 边距
        $config['offset'] = [
            'x' => intval($config['offset']['x']),
            'y' => intval($config['offset']['y'])
        ];

        // 图片水印
        if ($config['type'] == 'image') {
            // 水印图片
            $config['image'] = $this->filepath($config['image'], 'image');
        }

        // 文字水印
        if ($config['type'] == 'text') {
            // 字体
            $config['font'] = [
                'file'  => $this->filepath($config['font']['file'], 'font'),
                'size'  => max(intval($config['font']['size']), 12),
                'color' => $this->color($config['font']['color'], $config['opacity']),
            ];

            // 位置
            $positions = explode('-', $this->positions[$config['position']]);

            foreach ($positions as $position) {
                if (in_array($position, array('top', 'bottom', 'middle'))) {
                    $config['font']['valign'] = $position;
                }
                if (in_array($position, array('left', 'right', 'center'))) {
                    $config['font']['align'] = $position;
                }
            }

            switch ($config['font']['align']) {
                case 'center':
                    $config['offset']['x'] = $image->width() / 2;
                    break;
                case 'right':
                    $config['offset']['x'] = $image->width() - $config['offset']['x'];
                    break;
            }

            switch ($config['font']['valign']) {
                case 'middle':
                    $config['offset']['y'] = $image->height() / 2;
                    break;
                case 'bottom':
                    $config['offset']['y'] = $image->height() - $config['offset']['y'];
                    break;
            }          
        }

        return $config;
    }

    /**
     * 获取字体或者图片文件的真实路径
     *
     * @param  string $path 原始路径
     * @param  string $type 类型，font or image
     * @return string
     */
    protected function filepath($path, $type)
    {
        $possible = [base_path($path), public_path($path), realpath($path)];

        foreach ($possible as $filepath) {
            if ($this->files->exists($filepath)) {
                return $filepath;
            }
        }
        throw new InvalidArgumentException("Watermark config [$type] [$path] not found.");
    }

    /**
     * 文字颜色与透明度合成转换，将hex转为rgba
     * 
     * @param  string|array $color 原始颜色值
     * @param  int $type 原始透明度
     * @return array
     */
    protected function color($color, $opacity)
    {
        $opacity = $opacity/100;

        // [255,255,255]
        if (is_array($color) && count($color) == 3) {
            return [$color[0], $color[1], $color[2], $opacity];
        }

        // #ff6600,ff66ff,f60
        if (is_string($color) && $color) {
            $hex  = str_replace('#', '', $color);
            if (strlen($hex)==3) {
                return [
                    hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)),
                    hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)),
                    hexdec(substr($hex, 2, 1) . substr($hex, 2, 1)),
                    $opacity
                ];
            }
            if (strlen($hex)==6) {
                return [
                    hexdec(substr($hex, 0, 2)),
                    hexdec(substr($hex, 2, 2)),
                    hexdec(substr($hex, 4, 2)),
                    $opacity
                ];                
            }
        }

        throw new InvalidArgumentException("Watermark config color [$color] not correct.");
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
