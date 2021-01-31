<?php

namespace Modules\Navbar\Models\QueryFilters;

use App\Support\Eloquent\QueryFilter;

class ItemFilter extends QueryFilter
{
    /**
     * The global filter
     */
    public function boot()
    {
        // $this->query->where('id', '>', 1);
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
     * 过滤导航条编号
     *
     * @param int $navbarId
     */
    public function navbarId(int $navbarId)
    {
        $this->query->where('navbar_id', $navbarId);
    }

    /**
     * 过滤父级编号
     *
     * @param int $parentId
     */
    public function parentId(int $parentId)
    {
        $this->query->where('parent_id', $parentId);
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
     * @param string $link
     */
    public function link(string $link)
    {
        $this->query->where('link', $link);
    }

    /**
     * 过滤自定义数据
     *
     * @param string $custom
     */
    public function custom(string $custom)
    {
        $this->query->searchIn('custom', $custom);
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
