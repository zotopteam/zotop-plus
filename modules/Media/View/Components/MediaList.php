<?php

namespace Modules\Media\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;
use App\Support\Facades\Filter;

class MediaList extends Component
{
    /**
     * 媒体数据集合
     *
     * @var
     */
    public $list;

    /**
     * 媒体数据集合
     *
     * @var
     */
    public $class;

    /**
     * 可以多选的类型，默认或者true为全部类型，多个类型支持数组或者英文逗号分隔的字符串
     *
     * @var
     */
    public $checkable;

    /**
     * 是否可以多选
     *
     * @var
     */
    public $mutiple;

    /**
     * 是否允许嵌套访问模式（如：点击文件夹进入下级）
     *
     * @var
     */
    public $nestable;

    /**
     * 允许的操作
     *
     * @var
     */
    public $action;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($list, $class = null, $checkable = true, $mutiple = true, $nestable = true, $action = true)
    {
        $this->list = $list;
        $this->class = $class;
        $this->checkable = $checkable;
        $this->mutiple = $mutiple;
        $this->nestable = $nestable;
        $this->action = $action;

        // 多选时可以使用拖拽选择
        if ($this->checkable) {
            $this->class .= $this->mutiple ? ' checkable selectable' : ' checkable';
            if (is_string($this->checkable)) {
                $this->checkable = explode(',', $this->checkable);
            }
        }
        
        // 字符串转为数组
        if ($this->action && is_string($this->action)) {
            $this->action = explode(',', $this->action);
        }
    }

    protected function getList()
    {
        $list = [];
        foreach ($this->list as $item) {
            $item->link = $this->getLink($item);
            $item->action = $this->getAction($item);
            $list[] = $item;
        }
        return $list;
    }

    /**
     * 获取访问连接
     * 1，文件夹返回访问连接
     * 2，图片返回预览连接
     *
     * @return string
     */
    public function getLink($item)
    {
        $link = null;

        if ($item->is_folder && $this->nestable) {
            // 合并当前所有参数(排除分页参数)
            $parameters = array_merge(
                app('router')->current()->parameters(),
                app('request')->except('page', 'keywords'),
                [
                    'folder_id' => $item->id,
                ]
            );

            $link = route(
                app('router')->current()->getName(),
                $parameters
            );
        }

        if ($item->type == 'image' && ($this->action === true || in_array('view', $this->action))) {
            $link = preview($item->diskpath);
        }

        return Filter::fire('media.item.link', $link, $item);
    }

    /**
     * 返回操作
     *
     * @return array
     */
    public function getAction($item)
    {
        $action = [];

        $action['show'] = [
            'text'  => trans('master.show'),
            'href'  => route('media.show', $item->id),
            'icon'  => 'fa fa-info-circle',
            'class' => 'js-open',
            'attrs' => [
                'data-width'  => '90%',
                'data-height' => '75%',
            ],
        ];

        if ($item->type == 'image') {
            $action['view'] = [
                'text'  => trans('master.view'),
                'icon'  => 'fa fa-eye',
                'class' => 'js-image',
                'attrs' => [
                    'data-url'   => preview($item->diskpath),
                    'data-title' => $item->name,
                    'data-info'  => size_format($item->size) . ' / ' . $item->width . 'px × ' . $item->width . 'px',
                ],
            ];
        }

        if ($item->disk && $item->path) {
            $action['download'] = [
                'text' => trans('master.download'),
                'href' => route('media.download', $item->id),
                'icon' => 'fa fa-download',
            ];
        }

        $action['move'] = [
            'text'  => trans('master.move'),
            'icon'  => 'fa fa-arrows-alt',
            'class' => 'js-move',
            'attrs' => [
                'data-id'    => $item->id,
                'data-url'   => route('media.move'),
                'data-title' => trans('master.move'),
            ],
        ];

        $action['rename'] = [
            'text'  => trans('master.rename'),
            'icon'  => 'fa fa-eraser',
            'class' => 'js-prompt',
            'attrs' => [
                'data-url'    => route('media.rename', [$item->id]),
                'data-prompt' => trans('master.rename.prompt'),
                'data-name'   => 'name',
                'data-value'  => $item->name,
            ],
        ];

        $action['delete'] = [
            'text'  => trans('master.delete'),
            'href'  => route('media.destroy', $item->id),
            'icon'  => 'fa fa-times',
            'class' => 'js-delete',
        ];

        $action = Filter::fire('media.item.action', $action, $item);

        // 默认返回全部
        if ($this->action === true) {
            return $action;
        }

        // 传入允许的action
        if (!empty($this->action)) {
            return Arr::only($action, $this->action);
        }

        return [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('media::components.media_grid')->with('list', $this->getList());
    }
}
