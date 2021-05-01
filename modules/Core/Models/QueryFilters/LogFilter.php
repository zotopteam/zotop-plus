<?php

namespace Modules\Core\Models\QueryFilters;

use Zotop\Support\Eloquent\QueryFilter;

class LogFilter extends QueryFilter
{
    /**
     * 搜索编号
     *
     * @param int $id
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function username(string $username)
    {
        $this->query->whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        });
    }

    /**
     * 搜索编号
     *
     * @param int $id
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function id(int $id)
    {
        $this->query->where('id', $id);
    }

    /**
     * 搜索关键词
     *
     * @param string $keywords
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function keywords(string $keywords)
    {
        $this->query->searchIn('url,request,content', $keywords);
    }

    /**
     * 搜索模块
     *
     * @param string $module
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function module(string $module)
    {
        $this->query->where('module', $module);
    }

    /**
     * 搜索控制器
     *
     * @param string $controller
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function controller(string $controller)
    {
        $this->query->where('controller', $controller);
    }

    /**
     * 搜索模块
     *
     * @param string $action
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function action(string $action)
    {
        $this->query->where('action', $action);
    }
}
