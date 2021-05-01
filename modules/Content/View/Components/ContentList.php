<?php

namespace Modules\Content\View\Components;

use Illuminate\View\Component;
use Zotop\Support\Facades\Filter;

class ContentList extends Component
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
     * 是否可选择
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
     * 是否可选择
     *
     * @var 
     */
    public $moveable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($list, $class = null, $checkable = true, $mutiple = true, $moveable = true)
    {
        $this->list      = $list;
        $this->class     = $class;
        $this->checkable = $checkable;
        $this->mutiple   = $mutiple;
        $this->moveable  = $moveable;

        // 多选时可以使用拖拽选择
        if ($this->checkable) {
            $this->class .= $this->mutiple ? ' checkable selectable' : ' checkable';
        }
    }

    protected function getList()
    {
        $list = [];
        foreach ($this->list as $item) {
            $item->link   = $this->getLink($item);
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

        if ($item->type == 'folder') {
            // 合并当前所有参数(排除分页参数)
            $parameters = array_merge(
                app('router')->current()->parameters(),
                app('request')->except('page', 'keywords'),
                [
                    'folder_id' => $item->id
                ]
            );

            $link = route(
                app('router')->current()->getName(),
                $parameters
            );
        } else if ($item->type == 'image') {
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
        ];

        if ($item->type == 'image') {
            $action['view'] = [
                'text'  => trans('master.view'),
                'icon'  => 'fa fa-eye',
                'class' => 'js-image',
                'attrs' => [
                    'data-url'   => $item->link,
                    'data-title' => $item->name,
                    'data-info'  => size_format($item->size) . ' / ' . $item->width . 'px × ' . $item->width . 'px',
                ],
            ];
        }

        if ($item->disk && $item->path) {
            $action['download'] = [
                'text'  => trans('master.download'),
                'href'  => route('media.download', $item->id),
                'icon'  => 'fa fa-download',
            ];
        }

        if ($this->moveable) {
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
        }

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

        return Filter::fire('media.item.action', $action, $item);
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
