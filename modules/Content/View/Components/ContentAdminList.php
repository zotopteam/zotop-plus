<?php

namespace Modules\Content\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;
use App\Support\Facades\Filter;
use Modules\Content\Models\Content;

class ContentAdminList extends Component
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
     * 是否可排序
     *
     * @var 
     */
    public $sortable;

    /**
     * 是否可以嵌套访问，禁止后，无法进入目录下级
     *
     * @var 
     */
    public $nestable;

    /**
     * 允许的操作
     *
     * @var array
     */
    public $action;

    /**
     * 视图
     *
     * @var string
     */
    public $view;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $list,
        $class     = null,
        $checkable = true,
        $mutiple   = true,
        $sortable  = null,
        $nestable  = true,
        $action    = true,
        $view      = null
    ) {
        $this->list      = $list;
        $this->class     = $class;
        $this->checkable = $checkable;
        $this->mutiple   = $mutiple;
        $this->sortable  = $sortable;
        $this->nestable  = $nestable;
        $this->action    = $action;
        $this->view      = $view;

        // 多选时可以使用拖拽选择
        if ($this->checkable) {
            $this->class .= $this->mutiple ? ' checkable selectable' : ' checkable';
        }

        if ($this->sortable) {
            $this->class .= ' sortable';
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

        return Filter::fire('content.item.link', $link, $item);
    }

    /**
     * 返回操作
     *
     * @return array
     */
    public function getAction($item)
    {
        $action = [];

        // 查看和预览
        $action['view'] = [
            'text'  => $item->status == 'publish' ? trans('content::content.view') : trans('content::content.preview'),
            'href'  => $item->url,
            'icon'  => 'fas fa-eye',
            'attrs' => ['target' => '_blank']
        ];

        // 修改
        $action['edit'] = [
            'text' => trans('master.edit'),
            'href' => route('content.content.edit', [$item->id]),
            'icon' => 'fas fa-edit',
        ];

        // 复制
        $action['duplicate'] = [
            'text'  => trans('master.duplicate'),
            'href' => route('content.content.duplicate', [$item->id]),
            'icon'  => 'fas fa-copy',
        ];

        // 移动
        $action['move'] = [
            'text'  => trans('master.move'),
            'icon'  => 'fa fa-arrows-alt',
            'class' => 'js-move',
            'attrs' => [
                'data-id'    => $item->id,
                'data-url'   => route('content.content.move'),
                'data-title' => trans('master.move'),
            ],
        ];

        // 置顶操作
        $action['stick'] = [
            'text'  => $item->stick ? trans('content::content.unstick') : trans('content::content.stick'),
            'href'  => route('content.content.stick', $item->id),
            'icon'  => $item->stick ? 'fa fa-arrow-circle-down' : 'fa fa-arrow-circle-up',
            'class' => 'js-post',
        ];

        // 排序
        if ($this->sortable) {
            $action['sort'] = [
                'text'  => trans('content::content.sort'),
                'href'  => route('content.content.sort', [$item->id]),
                'icon'  => 'fa fa-sort-amount-up',
                'class' => 'js-open',
                'attrs' => ['data-width' => '80%', 'data-height' => '70%']
            ];
        }

        // 回收站中的数据可以永久删除
        if ($item->status == 'trash') {
            $action['delete'] = [
                'text'  => trans('content::content.destroy'),
                'href'  => route('content.content.destroy', [$item->id]),
                'icon'  => 'fa fa-times',
                'class' => 'js-delete',
            ];
        }

        foreach (Content::status() as $status => $value) {

            // 不显示自身状态和定时发布状态，定时发布取决于发布时间，如果发布时，时间是未来时间，则自动判定为定时发布
            if ($status == $item->status || $status == 'future') {
                continue;
            }

            $action[$status] = [
                'text'  => $value['text'],
                'href'  => route('content.content.status', [$status, $item->id]),
                'icon'  => $value['icon'],
                'class' => 'js-post',
            ];
        }

        // 待审状态时，发布显示通过审核
        if ($item->status == 'pending') {
            $action['publish']['text'] = trans('content::content.approved') . ' & ' . $action['publish']['text'];
        }

        $action = Filter::fire('content.item.action', $action, $item);

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
        // 返回空数据
        if (!$this->list->count()) {
            return view('components.empty');
        }

        // 快捷视图
        if (in_array($this->view, ['grid', 'list'])) {
            $view = "content::components.{$this->view}";
        } else {
            $view = $this->view;
        }

        return view($view)->with('list', $this->getList());
    }
}
