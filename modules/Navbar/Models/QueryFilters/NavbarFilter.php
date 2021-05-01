<?php

namespace Modules\Navbar\Models\QueryFilters;

use Zotop\Support\Eloquent\QueryFilter;

class NavbarFilter extends QueryFilter
{
    /**
     * The global filter
     */
    public function boot()
    {
        // $this->query->where('id', '>', 1);
    }

    /**
     * 关键词搜索
     *
     * @param string $keywords
     */
    public function keywords(string $keywords)
    {
        $this->query->searchIn('title', $keywords);
    }

    /**
     * 过滤id
     *
     * @param int $id
     */
    public function id(int $id)
    {
        $this->query->where('id', $id);
    }

    /**
     * 过滤标题
     *
     * @param string $title
     */
    public function title(string $title)
    {
        $this->query->where('title', $title);
    }

    /**
     * 过滤链接地址
     *
     * @param string $slug
     */
    public function slug(string $slug)
    {
        $this->query->where('slug', $slug);
    }

    /**
     * 过滤排序
     *
     * @param int $sort
     */
    public function sort(int $sort)
    {
        $this->query->where('sort', $sort);
    }

    /**
     * 过滤状态 1=启用 0=禁用
     *
     * @param string $status
     */
    public function status(string $status)
    {
        $this->query->where('status', $status);
    }

    /**
     * 过滤created_at
     *
     * @param string $createdAt
     */
    public function createdAt(string $createdAt)
    {
        $this->query->where('created_at', $createdAt);
    }

    /**
     * 过滤updated_at
     *
     * @param string $updatedAt
     */
    public function updatedAt(string $updatedAt)
    {
        $this->query->where('updated_at', $updatedAt);
    }

}
