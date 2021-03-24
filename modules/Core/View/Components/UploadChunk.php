<?php

namespace Modules\Core\View\Components;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class UploadChunk extends Component
{
    /**
     * 组件id
     * @var string
     */
    public $id;

    /**
     * 按钮文字
     * @var string
     */
    public $text;

    /**
     * 上传提示文字，如：请选择图片
     * @var string
     */
    public $title;

    /**
     * 按钮图标
     * @var string
     */
    public $icon;

    /**
     * 上传类型
     * @var string
     */
    public $type;

    /**
     * 上传最大尺寸
     * @var string
     */
    public $maxsize;


    /**
     * 传递参数
     * @var array
     */
    public $params;

    /**
     * 上传url
     * @var string
     */
    public $url;

    /**
     * 是否允许同时上传多个文件
     * @var boolean
     */
    public $mutiple;

    /**
     * Create a new component instance.
     *
     * @param null $id
     * @param string $icon
     * @param null $text
     * @param null $title
     * @param null $type
     * @param null $extensions
     * @param array $params
     * @param null $url
     * @param bool $mutiple
     * @param int $maxsize
     */
    public function __construct(
        $id         = null,
        $icon       = 'fa fa-upload',
        $text       = null,
        $title      = null,
        $type       = null,
        $extensions = null,
        $params     = [],
        $url        = null,
        $mutiple    = true,
        $maxsize    = 0
    ) {
        $this->id         = $id ?? 'upload' . time();
        $this->text       = $text ?? trans('core::file.upload');
        $this->icon       = $icon;
        $this->type       = $type;
        $this->extensions = $extensions;
        $this->params     = $params;
        $this->url        = $url;
        $this->maxsize    = (int) $maxsize;
        $this->title      = $title;
        $this->mutiple    = (bool) $mutiple;
    }

    /**
     * 获取上传参数
     * @return array
     */
    protected function getOptions()
    {
        // 如果没有扩展名，则获取对应type的扩展名，获取不到则返回 *
        if (empty($this->extensions)) {
            $this->extensions = config("core.upload.types.{$this->type}.extensions", '*');
        }

        // 如果没有传入type
        if (empty($this->maxsize)) {
            $this->maxsize = config("core.upload.types.{$this->type}.maxsize", 1024);
        }

        // 上传url
        if (empty($this->url)) {
            $this->url = route('core.file.upload_chunk', array_merge(Route::current()->parameters(), Request::all()));
        }

        // 上传提示文字
        if (empty($this->title)) {
            $this->title = trans("core::file.type." . ($this->type ?? 'null'));
        }

        // 返回组装参数
        return [
            'url'              => $this->url,
            'autostart'        => true,
            'multi_selection'  => $this->mutiple,
            'multipart_params' => $this->params,
            'filters' => [
                'max_file_size' => "{$this->maxsize}mb",
                'mime_types' => [[
                    'title'      => $this->title,
                    'extensions' => $this->extensions,
                ]],
                'prevent_duplicates' => false,
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('core::components.upload_chunk')->with('options', $this->getOptions());
    }
}
