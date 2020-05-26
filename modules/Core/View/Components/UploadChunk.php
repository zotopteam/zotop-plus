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
     * 上传path
     * @var string
     */
    public $path;  

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $id         = null,
        $text       = null,
        $icon       = 'fa fa-upload',
        $type       = null,
        $extensions = null,
        $params     = [],
        $url        = null,
        $path       = null
    )
    {
        $this->id         = $id ?? 'upload'.time();
        $this->text       = $text ?? trans('core::file.upload');
        $this->icon       = $icon;
        $this->type       = $type;
        $this->extensions = $extensions;
        $this->params     = $params;
        $this->url        = $url;
        $this->path       = $path;
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

        // 上传url
        if (empty($this->url)) {
            $this->url = route('core.file.upload_chunk', array_merge(Route::current()->parameters(), Request::all()));
        }

        // 上传参数
        $this->params = array_merge([
            'module'     => app('current.module'),
            'controller' => app('current.controller'),
            'action'     => app('current.action'),
            'path'       => $this->path,
        ], (array)$this->params);

        // 返回组装参数
        return [
            'url'              => $this->url,
            'autostart'        => true,
            'multi_selection'  => true,
            'multipart_params' => $this->params,
            'filters' => [
                'max_file_size' => '10mb',
                'mime_types' => [[
                    'title'      => $this->type ? trans("core::file.type.{$this->type}") : trans("core::file.type.null"),
                    'extensions' => $this->extensions,
                ]],
                'prevent_duplicates' => true,
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
