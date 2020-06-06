<?php

namespace Modules\Media\View\Components;

use Illuminate\View\Component;

class MediaBreadcrumb extends Component
{
    /**
     * 当前文件夹Media对象
     *
     * @var mixed
     */
    public $media;

    /**
     * 当前面包屑的样式
     *
     * @var mixed
     */
    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($media, $class = null)
    {
        $this->media = $media;
        $this->class = $class ? 'breadcrumb ' . $class : 'breadcrumb';
    }

    /**
     * 获取连接
     *
     * @param integer $folder_id 目录编号
     * @return void
     */
    protected function getHref($folder_id)
    {
        // 合并当前所有参数(排除分页参数)
        $parameters = array_merge(
            app('router')->current()->parameters(),
            app('request')->except('page'),
            [
                'folder_id' => $folder_id
            ]
        );

        return route(
            app('router')->current()->getName(),
            $parameters
        );
    }



    /**
     * 获取面包屑的父路径数据
     *
     * @return array
     */
    protected function getBreadcrumb()
    {
        $breadcrumb = [];

        // 根目录
        $breadcrumb['root'] = [
            'text' => trans('media::media.root'),
            'icon' => 'fa fa-folder',
            'href' => $this->getHref(0),
        ];

        if ($this->media) {
            foreach ($this->media->parents as $key => $item) {
                $breadcrumb[$key] = [
                    'text' => $item->name,
                    'icon' => 'fa fa-folder',
                    'href' => $this->getHref($item->id),
                ];
            }
        }

        return $breadcrumb;
    }

    /**
     * 获取上级连接
     *
     * @return array
     */
    protected function getUpHref($breadcrumb)
    {
        // 导航最后一项为当前，所以去除
        array_pop($breadcrumb);

        // 去除当前之后导航的最后一个就是上一级
        $item = last($breadcrumb);

        return $item ? $item['href'] : null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $breadcrumb = $this->getBreadcrumb();
        $up_href    = $this->getUpHref($breadcrumb);

        return view('media::components.media_breadcrumb')
            ->with('up_href', $up_href)
            ->with('breadcrumb', $breadcrumb);
    }
}
