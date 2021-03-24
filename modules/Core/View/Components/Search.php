<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;

class Search extends Component
{
    /**
     * 搜索地址
     *
     * @var string
     */
    public $action;

    /**
     * 搜索参数
     *
     * @var array
     */
    public $params;

    /**
     * 取消地址
     *
     * @var mixed
     */
    public $cancel;

    /**
     * 搜索文字，默认为空
     *
     * @var string
     */
    public $search;

    /**
     * 关键词占位符
     *
     * @var string
     */
    public $placeholder;

    /**
     * 样式
     *
     * @var string
     */
    public $class;

    /**
     * 视图文件
     *
     * @var array
     */
    public $view;

    /**
     * Create a new component instance.
     *
     * @param bool $action
     * @param null $params
     * @param array $except
     * @param bool $cancel
     * @param null $class
     * @param null $placeholder
     * @param null $search
     * @param string $view
     */
    public function __construct(
        $action = true,
        $params = null,
        $except = [],
        $cancel = true,
        $class = null,
        $placeholder = null,
        $search = null,
        $view = 'core::components.search'
    )
    {
        $this->action = $this->getAction($action);
        $this->params = $this->getParameters($action, $params);
        $this->cancel = $this->getCancel($cancel);
        $this->class = $class ? 'form form-search ' . $class : 'form form-search form-inline d-inline-flex';
        $this->placeholder = $placeholder ?? trans('master.search.placeholder');
        $this->search = $search;
        $this->view = $view;
    }

    /**
     * 获取搜索的附加参数
     *
     * @param mixed $url
     * @param mixed $params
     * @return array
     */
    protected function getParameters($url, $params)
    {
        // 参数必须是数据
        $params = is_array($params) ? $params : [];

        // 合并url中的参数
        if (is_string($url) && !empty($url)) {
            $request = app('request')->create($url);
        } else {
            $request = app('request');
        }

        // 剔除关键词和页码
        $query = $request->except(['keywords', 'page']);

        return array_merge($query, $params);
    }

    /**
     * 获取动作连接
     *
     * @param mixed $url
     * @return string
     */
    protected function getAction($url)
    {
        // 如果是链接，解析并去掉url后面的参数
        // GET 表单无法保持url后面的参数，参数通过 params 传递
        if (is_string($url) && !empty($url)) {
            return app('request')->create($url)->url();
        }

        return request()->url();
    }

    /**
     * 获取链接
     *
     * @param mixed $url
     * @return string
     */
    protected function getCancel($url)
    {
        // 如果是链接，直接返回
        if (is_string($url) && !empty($url)) {
            return $url;
        }

        // 如果是true，返回当前链接（去掉keywords）
        if ($url === true) {
            // 合并当前所有参数(排除分页参数)
            $parameters = array_merge(
                app('router')->current()->parameters(),
                app('request')->except('keywords')
            );

            return route(
                app('router')->current()->getName(),
                $parameters
            );
        }

        return null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view($this->view);
    }
}
