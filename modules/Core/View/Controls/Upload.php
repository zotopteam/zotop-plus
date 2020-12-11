<?php

namespace Modules\Core\View\Controls;

use App\Modules\Facades\Module;
use App\Support\Form\Control;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Upload extends Control
{

    /**
     * Create a new control instance.
     *
     * @return void
     */
    public function __construct(
        $id = null,
        $sourceId = null,
        $enable = null,
        $mutiple = false,
        $class = null,
        $view = null,
        $url = null,
        $params = [],
        $fileType = null,
        $fileTypeName = null,
        $maxSize = null,
        $chunkSize = null,
        $allow = null,
        $buttonIcon = null,
        $buttonText = null,
        $selectText = null,
        $preview = null,
        $input = null,
        $tools = [],
        $options = []
    )
    {
        $this->id = $id;
        $this->sourceId = $sourceId;
        $this->enable = $enable;
        $this->mutiple = $mutiple;
        $this->class = $class;
        $this->view = $view;
        $this->url = $url ?? route('core.file.upload_chunk');
        $this->params = Arr::wrap($params);
        $this->chunkSize = $chunkSize ?? config('core.upload.chunk_size', '2mb');
        $this->maxSize = $maxSize;
        $this->fileType = $fileType;
        $this->fileTypeName = $fileTypeName;
        $this->allow = $allow;
        $this->buttonIcon = $buttonIcon ?? 'fa fa-upload';
        $this->buttonText = $buttonText;
        $this->selectText = $selectText;
        $this->preview = $preview;
        $this->input = $input ?? true;
        $this->tools = $tools ?? true;
        $this->options = Arr::wrap($options);
    }

    /**
     * 初始化控件
     *
     * @author Chen Lei
     * @date 2020-12-07
     */
    public function bootUpload()
    {
        $types = collect(config('core.upload.types'));

        if (Str::startsWith($this->type, 'upload-')) {
            // 文件类型
            $this->fileType = $this->type = Str::substr($this->type, 7);
            // 允许后缀名
            $this->allow = $this->allow ?? config("core.upload.types.{$this->fileType}.extensions");
            $this->maxSize = $this->maxSize ?? config("core.upload.types.{$this->fileType}.maxsize") . 'mb';
            $this->fileTypeName = $this->fileTypeName ?? trans("core::file.type.{$this->fileType}");
            $this->buttonText = $this->buttonText ?? trans('core::control.upload.type', [$this->fileTypeName]);
            $this->selectText = $this->selectText ?? trans('core::control.select.type', [$this->fileTypeName]);
            $this->enable = $this->enable ?? config("core.upload.types.{$this->fileType}.enabled");
        }

        $this->allow = $this->allow ?? $types->implode('extensions', ',');
        $this->enable = $this->enable ?? true;
        $this->maxSize = $this->maxSize ?? '1024mb';
        $this->fileTypeName = $this->fileTypeName ?? trans('core::file.type.files');
        $this->buttonText = $this->buttonText ?? trans('core::control.upload');
        $this->selectText = $this->selectText ?? trans('core::control.select');
    }

    /**
     * 初始化类型
     *
     * @author Chen Lei
     * @date 2020-12-07
     */
    public function bootUploadImage()
    {
        // 图片类型默认开启预览
        $this->preview = $this->preview ?? true;
    }

    /**
     * 上传参数
     *
     * @return array
     * @date 2020-12-07
     * @author Chen Lei
     */
    protected function params()
    {
        return array_merge([
            'mutiple'    => $this->mutiple,
            'type'       => $this->fileType,
            'module'     => app('current.module'),
            'controller' => app('current.controller'),
            'action'     => app('current.action'),
            'field'      => $this->attributes->get('name'),
            'source_id'  => $this->sourced,
            'user_id'    => Auth::user()->id ?? 0,
            'token'      => Auth::user()->token ?? '',
        ], $this->params);
    }

    /**
     * options
     *
     * @param array $options
     * @return array
     * @author Chen Lei
     * @date 2020-12-06
     */
    protected function options(array $options)
    {
        $default = [
            'url'              => $this->url,
            'chunk_size'       => $this->chunkSize,
            'multipart_params' => $this->params(),
            'filters'          => [
                'max_file_size'      => $this->maxSize,
                'mime_types'         => [[
                    'title'      => $this->selectText,
                    'extensions' => $this->allow,
                ]],
                'prevent_duplicates' => false,
            ],
        ];

        // 标签上取出的属性
        $attributes = $this->attributes->pull(array_keys($default));

        // 设置属性
        $options = attribute($options)->merge($default, false)->merge($attributes->toArray());

        return $options->toArray();
    }

    /**
     * 上传工具
     *
     * @param bool|string|array $tools
     * @return array
     * @author Chen Lei
     * @date 2020-12-07
     */
    protected function tools($tools)
    {
        if ($tools === false || (is_string($tools) && empty($tools))) {
            return [];
        }

        // 默认工具
        $defaultTools = Module::data('core::field.upload.tools', $this->params());

        // 如果是数组，合并到默认工具中
        if (is_array($tools)) {
            return array_merge($defaultTools, $tools);
        }

        return $defaultTools;
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        $this->options = $this->options($this->options);
        $this->tools = $this->tools($this->tools);

        // 添加 preview 和 data-type 标签
        $this->attributes->set('preview', $this->preview)->addData('type', $this->type);

        debug($this->attributes);

        return $this->view($this->view ?? 'core::controls.upload');
    }
}
