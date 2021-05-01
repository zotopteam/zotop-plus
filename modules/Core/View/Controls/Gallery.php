<?php

namespace Modules\Core\View\Controls;

use Zotop\Modules\Facades\Module;
use Zotop\Support\Form\Control;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Gallery extends Control
{

    /**
     * Create a new control instance.
     *
     * @param string|null $id
     * @param string|null $name
     * @param array $value
     * @param string|null $placeholder
     * @param string|null $sourceId
     * @param string|null $enable
     * @param string|null $class
     * @param string|null $view
     * @param string|null $url
     * @param array $params
     * @param string|null $fileTypeName
     * @param string|null $maxSize
     * @param string|null $chunkSize
     * @param string|null $allow
     * @param string|null $buttonIcon
     * @param string|null $buttonText
     * @param string|null $selectText
     * @param array $tools
     * @param array $options
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = [],
        $placeholder = null,
        $sourceId = null,
        $enable = null,
        $class = null,
        $view = null,
        $url = null,
        $params = [],
        $fileTypeName = null,
        $maxSize = null,
        $chunkSize = null,
        $allow = null,
        $buttonIcon = null,
        $buttonText = null,
        $selectText = null,
        $tools = [],
        $options = []
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = Arr::wrap($value);
        $this->placeholder = $placeholder ?? trans('core::control.gallery.placeholder');
        $this->sourceId = $sourceId;
        $this->enable = $enable ?? config("core.upload.types.image.enabled");
        $this->class = $class;
        $this->view = $view;
        $this->url = $url ?? route('core.file.upload_chunk');
        $this->params = Arr::wrap($params);
        $this->chunkSize = $chunkSize ?? config('core.upload.chunk_size', '2mb');
        $this->maxSize = $maxSize ?? config("core.upload.types.image.maxsize") . 'mb';
        $this->fileType = 'image';
        $this->fileTypeName = $fileTypeName ?? trans("core::file.type.image");
        $this->allow = $allow ?? config("core.upload.types.image.extensions");
        $this->buttonIcon = $buttonIcon ?? 'fa fa-upload';
        $this->buttonText = $buttonText ?? trans('core::control.upload.multiple');
        $this->selectText = $selectText ?? trans('core::control.select');
        $this->tools = $tools ?? true;
        $this->options = Arr::wrap($options);

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
            'mutiple'    => true,
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

        return $this->view($this->view ?? 'core::controls.gallery');
    }
}
