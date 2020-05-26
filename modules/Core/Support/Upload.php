<?php

namespace Modules\Core\Support;

use App\Support\Facades\Filter;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Upload
{
    /**
     * 错误信息
     * @var string
     */
    protected $error;

    /**
     * 上传默认目录
     */
    protected $directory;

    /**
     * 默认上传磁盘为public
     * @var string
     */
    protected $disk;

    /**
     * 全部上传类型
     * @var array
     */
    protected $types;

    /**
     * 请求
     * @var Illuminate\Http\Request
     */
    public $request;

    /**
     * 上传文件对象
     * @var Illuminate\Http\UploadedFile
     */
    public $file;

    /**
     * 文件真实路径
     * @var string
     */
    public $realpath;    

    /**
     * 文件路径
     * @var string
     */
    public $path;

    /**
     * 文件类型
     * @var string
     */
    public $type;

    /**
     * 扩展名
     * @var string
     */
    public $extension;

    /**
     * 文件资源类型
     * @var string
     */
    public $mimetype;

    /**
     * 文件名称
     * @var string
     */
    public $name;

    /**
     * 文件大小
     * @var integer
     */
    public $size;

    /**
     * 图片宽度
     * @var integer
     */
    public $width = 0;

    /**
     * 图片高度
     * @var integer
     */
    public $height = 0;

    /**
     * 初始化
     */
    public function __construct(UploadedFile $file)
    {
        $this->file      = $file;
        $this->request   = app('request');
        $this->disk      = $this->disk();
        $this->directory = $this->directory();
        $this->types     = config('core.upload.types');
        
        $this->realpath  = $this->file->getRealPath();
        $this->type      = $this->file->getHumanType();
        $this->extension = $this->file->getClientOriginalExtension();
        $this->size      = $this->file->getSize();
        $this->mimetype  = $this->file->getMimeType();
        $this->hash      = $this->file->getHash();
        $this->name      = $this->request->input('filename', $this->file->getClientOriginalName());
    }

    /**
     * 静态实例化
     * @param  UploadedFile $file 上传对象
     * @return object
     */
    public static function file(UploadedFile $file)
    {
        return new static($file);
    }

    /**
     * 保存上传文件，返回上传文件详情数组
     * @return array
     */
    public function save()
    {
        // 检查上传文件是否有错误
        if ($error = $this->hasError()) {
            return $this->error($error);
        }

        // 存储文件
        $file = Storage::disk($this->disk)->putFileAs($this->directory, $this->file, $this->hash.'.'.$this->extension);

        // 获取文件信息
        $this->path     = $file;
        $this->url      = Storage::disk($this->disk)->url($file);

        // 获取图片信息
        if ($this->type == 'image') {
            // 获取宽高和大小
            $image = Storage::disk($this->disk)->get($this->path);
            $image = Image::make($image);
            // $this->size   = $image->filesize();
            $this->width  = $image->width();
            $this->height = $image->height();            
        }        

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
     * 设置或获取存储的磁盘
     * @param  string $disk
     * @return mixed
     */
    public function disk($disk=null)
    {
        if ($disk) {
            $this->disk = $disk;
            return $this;
        }

        return $this->request->input('disk', 'public');
    }

    /**
     * 设置或获取上传文件的存储目录
     * @param  string $directory
     * @return mixed
     */
    public function directory($directory=null)
    {
        if ($directory) {
            $this->directory = $directory;
            return $this;
        }

        // 默认存储路径
        $directory = 'uploads' . $this->type . '/' . date(config('core.upload.dir', 'Y/m/d'), time());

        return $this->request->input('dir', $directory);
    }

    /**
     * 上传内容处理，如图片缩放或者水印等
     * @return void
     */
    public function process()
    {
        if ($this->type == 'image') {
            
            try {
                // 图片缩放
                //app(Resize::class)->with($this->request->input('resize', []))->apply($this->realpath);
                // 图片水印
                //app(Watermark::class)->with($this->request->input('watermark', []))->apply($this->realpath);
            } catch (\Exception $e) {
            }
            
            // 获取宽高和大小
            $image = Storage::disk($this->disk)->get($this->path);
            $image = app('image')->make($image);

            $this->size   = $image->filesize();
            $this->width  = $image->width();
            $this->height = $image->height();            
        }
    }

    /**
     * 是否允许上传格式
     * @return boolean
     */
    public function hasError()
    {
        // 检查文件类型
        if (empty($this->type)) {
            return 'type';            
        }

        // 检查文件类型是否开启
        if (! Arr::get($this->types, "{$this->type}.enabled", 0)) {
            return 'enabled';
        }

        // 检查文件格式
        if ($extensions = Arr::get($this->types, "{$this->type}.extensions", '')) {
            if (! in_array($this->extension, explode(',', $extensions))) {
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
     * @param  string $error 错误类型
     * @return array
     */
    public function error($error)
    {
        // 上传失败直接删除文件
        Storage::disk($this->disk)->delete($this->path);

        return [
            'state' => false,
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
     * @param  array $data 文件详细信息
     * @return array
     */
    public function success($data)
    {
        $data = array_merge([
            'state' => true,
            'content' => trans('core::file.upload.success',[
                'type'      => $this->type,
                'name'      => $this->name,
                'extension' => $this->extension,
                'size'      => size_format($this->size),
            ]),
        ], $data);

        return Filter::fire('core.file.upload', $data, $this);  
    }

}
