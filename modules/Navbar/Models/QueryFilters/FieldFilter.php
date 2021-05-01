<?php

namespace Modules\Navbar\Models\QueryFilters;

use Zotop\Support\Eloquent\QueryFilter;

class FieldFilter extends QueryFilter
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
     * 过滤导航父编号
     *
     * @param int $parentId
     */
    public function parentId(int $parentId)
    {
        $this->query->where('parent_id', $parentId);
    }

    /**
     * 过滤显示的标签名称
     *
     * @param string $label
     */
    public function label(string $label)
    {
        $this->query->where('label', $label);
    }

    /**
     * 过滤控件类型，如text，number等
     *
     * @param string $type
     */
    public function type(string $type)
    {
        $this->query->where('type', $type);
    }

    /**
     * 过滤字段名称
     *
     * @param string $name
     */
    public function name(string $name)
    {
        $this->query->where('name', $name);
    }

    /**
     * 过滤默认值
     *
     * @param string $default
     */
    public function default(string $default)
    {
        $this->query->searchIn('default', $default);
    }

    /**
     * 过滤控件设置，如radio，select等的选项
     *
     * @param string $settings
     */
    public function settings(string $settings)
    {
        $this->query->searchIn('settings', $settings);
    }

    /**
     * 过滤控件提示信息
     *
     * @param string $help
     */
    public function help(string $help)
    {
        $this->query->where('help', $help);
    }

    /**
     * 过滤排序字段
     *
     * @param int $sort
     */
    public function sort(int $sort)
    {
        $this->query->where('sort', $sort);
    }

    /**
     * 过滤是否禁用，0：启用，1：禁用
     *
     * @param string $disabled
     */
    public function disabled(string $disabled)
    {
        $this->query->where('disabled', $disabled);
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
