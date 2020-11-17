<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImagePreview
{
    /**
     * 存储磁盘
     *
     * @var string
     */
    public $disk;

    /**
     * 文件路径，当disk=null为绝对路径模式，否则为存储模式：public:uploads/2020/05/1.jpg
     *
     * @var string
     */
    public $path;

    /**
     * 图片最大宽度
     *
     * @var integer
     */
    public $width;

    /**
     * 图片最大高度
     *
     * @var integer
     */
    public $height;

    /**
     * 滤镜
     *
     * @var string
     */
    public $filter = 'original';

    /**
     * 初始化
     *
     * @param string $path 文件路径
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        // 磁盘模式
        if (strpos($this->path, ':')) {
            $this->disk = Str::before($this->path, ':');
            $this->path = Str::afterLast($this->path, ':');
        }
    }

    /**
     * 静态初始化
     *
     * @param string $path 文件路径
     * @return $this
     */
    public static function file(string $path)
    {
        return new static($path);
    }

    /**
     * 文件路径md5 hash值
     *
     * 1，存储盘路径将使用 disk:path 模式
     * 2，绝对路径将转化为站点根目录起始的相对路径
     *
     * @return string
     */
    private function pathHash()
    {
        if ($this->disk) {
            return md5($this->disk . ':' . $this->path);
        }

        return md5(path_base($this->path));
    }

    /**
     * 静态预览图存储相对路径
     * 在预览图目录中，按照文件路径的hash值建立文件夹，存储该图片的全部预览图文件
     *
     * @return string
     */
    private function staticPath()
    {
        $hash = $this->pathHash();

        $path = config('image.preview.static.directory', 'previews/images');
        $path = $path . '/' . substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . $hash;
        $path = $path . '/' . md5("{$hash}-{$this->filter}-{$this->width}-{$this->height}") . '.' . File::extension($this->path);

        return $path;
    }

    /**
     * 删除静态预览图
     *
     * @return mixed
     */
    public function staticDelete()
    {
        // 预览图存储相对路径的存储目录
        $path = dirname($this->staticPath());

        // 删除图片的所有存储的静态预览图
        File::deleteDirectory(public_path($path));

        // 删除全部空目录
        while (true) {
            // 获取上级目录
            $path = dirname($path);
            // 目录不为空则删除失败，跳出循环
            if (!@rmdir(public_path($path))) {
                break;
            }
        }

        return true;
    }

    /**
     * 获取静态访问url
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function staticUrl()
    {
        // 预览图存储相对路径
        $path = $this->staticPath();

        // 预览文件绝对路径
        $file = public_path($path);

        // 源文件是否存在
        $exists = $this->disk ? Storage::disk($this->disk)->exists($this->path) : File::exists($this->path);

        // 源文件存在
        if ($exists) {

            // 源文件最后修改时间
            $lastModified = $this->disk ? Storage::disk($this->disk)->lastModified($this->path) : File::lastModified($this->path);

            // 预览文件不存在或者源文件被修改
            if (!File::exists($file) || File::lastModified($file) < $lastModified) {

                // 如果目录不存在，尝试创建
                if (!File::isDirectory($dir = dirname($file))) {
                    File::makeDirectory($dir, 0775, true);
                }

                // 生成预览图
                $image = $this->disk ? Storage::disk($this->disk)->get($this->path) : $this->path;
                $image = Image::make($image);
                $image = ImageFilter::apply($image, $this->filter, [
                    'width'  => $this->width,
                    'height' => $this->height,
                ]);
                $image->save($file);
            }
        }

        return url($path);
    }

    /**
     * 获取动态访问url
     *
     * @return string
     */
    public function dynamicUrl()
    {
        // 组装滤镜 resize:300-300 fit:300-200
        if ($this->width = intval($this->width)) {
            $this->height = intval($this->height) ? $this->height : $this->width;
            $this->filter = "{$this->filter}:{$this->width}-{$this->height}";
        }

        // 绝对路径模式时 disk=root，path 转化为相对路径
        if (empty($this->disk)) {
            $this->disk = 'root';
            $this->path = path_base($this->path);
        }

        return route('preview.image', [
            'disk'   => $this->disk,
            'path'   => $this->path,
            'filter' => $this->filter,
        ]);
    }

    /**
     * 根据设置自动判断返回的url方式
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function url()
    {
        // TODO 如果存储盘为远程盘，直接全部使用动态模式
        if (config('image.preview.mode') == 'static') {
            return $this->staticUrl();
        }

        return $this->dynamicUrl();
    }

    /**
     * 动态方法
     *
     * @param string $method
     * @param array $parameters
     * @return $this
     *
     * @throws \Exception
     */
    public function __call(string $method, array $parameters)
    {
        // 如果当前类存在属性，则直接赋值给属性
        if ($parameters && property_exists($this, $method)) {
            $this->$method = reset($parameters);
            return $this;
        }

        throw new \Exception('Call to undefined method ' . get_class($this) . "::{$method}()");
    }
}
