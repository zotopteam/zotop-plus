<?php

namespace Modules\Core\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Zotop\Hook\Facades\Filter;
use Zotop\Image\Filter as ImageFilter;

class Upload
{
    /**
     * 错误信息
     *
     * @var string
     */
    protected $error;

    /**
     * 上传默认目录
     */
    protected $directory;

    /**
     * 默认上传磁盘为public
     *
     * @var string
     */
    protected $disk;

    /**
     * 全部上传类型
     *
     * @var array
     */
    protected $types;


    /**
     * 请求
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * 上传文件对象
     *
     * @var \Illuminate\Http\UploadedFile
     */
    public $file;

    /**
     * 文件路径
     *
     * @var string
     */
    public $path;

    /**
     * 文件类型
     *
     * @var string
     */
    public $type;

    /**
     * 扩展名
     *
     * @var string
     */
    public $extension;

    /**
     * 文件资源类型
     *
     * @var string
     */
    public $mimetype;

    /**
     * 文件名称
     *
     * @var string
     */
    public $name;

    /**
     * 文件大小
     *
     * @var integer
     */
    public $size;

    /**
     * 图片宽度
     *
     * @var integer
     */
    public $width = 0;

    /**
     * 图片高度
     *
     * @var integer
     */
    public $height = 0;

    /**
     * 图片滤镜
     *
     * @var integer
     */
    public $filters;

    /**
     * 初始化
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->request = app('request');
        $this->types = config('core.upload.types');

        $this->realpath = $this->file->getRealPath();
        $this->type = $this->file->getHumanType();
        $this->extension = $this->file->getClientOriginalExtension();
        $this->size = $this->file->getSize();
        $this->mimetype = $this->file->getMimeType();
        $this->hash = $this->file->getHash();
        $this->name = $this->request->input('filename', $this->file->getClientOriginalName());

        $this->disk = $this->disk();
        $this->directory = $this->directory();
        $this->filters = $this->filters();
    }

    /**
     * 静态实例化
     *
     * @param UploadedFile $file 上传对象
     * @return object
     */
    public static function file(UploadedFile $file)
    {
        return new static($file);
    }

    /**
     * 保存上传文件，返回上传文件详情数组
     *
     * @return array
     */
    public function save()
    {
        // 检查上传文件是否有错误
        if ($error = $this->hasError()) {
            return $this->error($error);
        }

        // 保存文件
        $this->path = $this->storageFile();
        $this->url = $this->storageUrl();

        // 过滤并返回数据
        $data = [
            'disk'      => $this->disk,
            'name'      => $this->name,
            'type'      => $this->type,
            'hash'      => $this->hash,
            'mimetype'  => $this->mimetype,
            'extension' => $this->extension,
            'size'      => $this->size,
            'width'     => $this->width,
            'height'    => $this->height,
            'path'      => $this->path,
            'url'       => $this->url,
        ];

        return $this->success($data);
    }

    /**
     * 存储文件，返回文件的存储路径（disk path）
     *
     * @return string
     */
    protected function storageFile()
    {
        // 因为要对图片加水印，所以单独处理图片存储
        if ($this->type == 'image') {

            $image = Image::make($this->file);

            // 应用图片滤镜
            $filters = is_array($this->filters) ? $this->filters : explode(',', $this->filters);

            foreach ($filters as $filter) {
                $image = ImageFilter::apply($image, $filter);
            }

            // 图片宽高
            $this->width = $image->width();
            $this->height = $image->height();
            $this->size = $image->filesize();

            // 存储图片
            $this->path = $this->directory . '/' . $this->storageName();

            if (Storage::disk($this->disk)->put($this->path, (string)$image->encode())) {
                return $this->path;
            }

            return null;
        }

        // 其余类型文件直接存储
        $this->path = Storage::disk($this->disk)->putFileAs($this->directory, $this->file, $this->storageName());
        return $this->path;
    }

    /**
     * 获取文件的存储url
     *
     * @return string|void
     */
    public function storageUrl()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * 文件存储名称 2020053012161412345.jpg
     *
     * @return string
     */
    protected function storageName()
    {
        return now()->format('YmdHisu') . rand(10000, 99999) . '.' . $this->extension;
    }


    /**
     * 设置或获取存储的磁盘
     *
     * @param string $disk
     * @return mixed
     */
    public function disk($disk = null)
    {
        if ($disk) {
            $this->disk = $disk;
            return $this;
        }

        return $this->request->input('disk', 'public');
    }

    /**
     * 设置或获取上传文件的存储目录
     *
     * @param string $directory
     * @return mixed
     */
    public function directory($directory = null)
    {
        if ($directory) {
            $this->directory = $directory;
            return $this;
        }

        // 默认存储路径
        $directory = 'uploads/' . $this->type . '/' . date(config('core.upload.dir', 'Y/m/d'), time());

        return $this->request->input('dir', $directory);
    }

    /**
     * 设置或获取图片滤镜
     *
     * @param mixed $filters
     * @return array|\Modules\Core\Support\Upload
     */
    public function filters($filters = [])
    {
        if ($filters) {
            $this->filters = $filters;
            return $this;
        }

        return $this->request->input('filters', ['core-resize', 'core-watermark']);
    }

    /**
     * 是否允许上传格式
     *
     * @return boolean
     */
    public function hasError()
    {
        // 检查文件类型
        if (empty($this->type)) {
            return 'type';
        }

        // 检查文件类型是否开启
        if (!Arr::get($this->types, "{$this->type}.enabled", 0)) {
            return 'enabled';
        }

        // 检查文件格式
        if ($extensions = Arr::get($this->types, "{$this->type}.extensions", '')) {
            if (!in_array($this->extension, explode(',', $extensions))) {
                return 'extension';
            }
        }

        // 检查文件大小
        if ($maxsize = Arr::get($this->types, "{$this->type}.maxsize", 0)) {
            if ($this->size > $maxsize * 1024 * 1024) {
                return 'maxsize';
            }
        }

        return null;
    }

    /**
     * 返回错误
     *
     * @param string $error 错误类型
     * @return array
     */
    public function error($error)
    {
        // 上传失败直接删除文件
        Storage::disk($this->disk)->delete($this->path);

        return [
            'state'   => false,
            'content' => trans("core::file.upload.error.{$error}", [
                'type'      => $this->type,
                'name'      => $this->name,
                'extension' => $this->extension,
                'size'      => size_format($this->size),
            ]),
        ];
    }

    /**
     * 返回成功
     *
     * @param array $data 文件详细信息
     * @return array
     */
    public function success($data)
    {
        $data = array_merge([
            'state'   => true,
            'content' => trans('core::file.upload.success', [
                'type'      => $this->type,
                'name'      => $this->name,
                'extension' => $this->extension,
                'size'      => size_format($this->size),
            ]),
        ], $data);

        return Filter::fire('core.file.upload', $data, $this);
    }
}
