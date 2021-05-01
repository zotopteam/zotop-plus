<?php

namespace Modules\Content\View\Controls;

use Zotop\View\Form\Control;
use Illuminate\Support\Arr;
use Modules\Content\Models\Model;

class ContentModels extends Control
{

    /**
     * Create a new control instance.
     *
     * @param string $id
     * @param string $name
     * @param mixed $value
     */
    public function __construct(
        $id = null,
        $name = null,
        $value = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = Arr::wrap($value);
    }

    /**
     * 启动控件
     *
     * @author Chen Lei
     * @date 2020-12-18
     */
    public function bootContentModels()
    {
        // 读取可用的模型，并合并当前设置值
        $this->models = Model::query()
            ->where('disabled', '0')
            ->orderBy('sort', 'asc')
            ->get()
            ->transform(function ($item) {

                // 视图控件名称和值
                $item->viewName = $this->name . "[{$item->id}][view]";
                $item->viewValue = Arr::get($this->value, $item->id . '.view', $item->view);

                // 启用控件名称和值
                $item->enabledName = $this->name . "[{$item->id}][enabled]";
                $item->enabledValue = Arr::get($this->value, $item->id . '.enabled', $this->value ? 0 : 1);

                return $item;
            })
            ->keyBy('id');
    }

    /**
     * 渲染控件
     *
     * @return \Closure|\Illuminate\Contracts\Support\Htmlable|\Illuminate\Contracts\View\View|\Illuminate\Support\HtmlString|string
     */
    public function render()
    {
        return $this->view('content::controls.content-models');
    }
}
