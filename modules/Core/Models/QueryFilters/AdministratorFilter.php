<?php

namespace Modules\Core\Models\QueryFilters;

use App\Support\Eloquent\QueryFilter;

class AdministratorFilter extends QueryFilter
{
    /**
     * 全局滤器
     */
    public function boot()
    {
        $this->query->whereIn('model_id', ['super', 'admin']);
    }

    /**
     * 搜索编号
     *
     * @param int $id
     */
    public function id(int $id)
    {
        $this->query->where('id', $id);
    }

    /**
     * 搜索用户名
     *
     * @param string $username
     */
    public function username(string $username)
    {
        $this->query->where('username', $username);
    }

    /**
     * 搜索邮箱
     *
     * @param string $email
     */
    public function email(string $email)
    {
        $this->query->where('email', $email);
    }

    /**
     * 搜索手机号
     *
     * @param string $mobile
     */
    public function mobile(string $mobile)
    {
        $this->query->where('mobile', $mobile);
    }

    /**
     * 搜索模型编号：如super,admin或者member
     *
     * @param string $modelId
     */
    public function modelId(string $modelId)
    {
        $this->query->where('model_id', $modelId);
    }

    /**
     * 搜索昵称
     *
     * @param string $nickname
     */
    public function nickname(string $nickname)
    {
        $this->query->where('nickname', $nickname);
    }

    /**
     * 搜索性别 0=保密 1=男 2=女
     *
     * @param string $gender
     */
    public function gender(string $gender)
    {
        $this->query->where('gender', $gender);
    }


    /**
     * 搜索登录次数
     *
     * @param int $loginTimes
     */
    public function loginTimes(int $loginTimes)
    {
        $this->query->where('login_times', $loginTimes);
    }

    /**
     * 搜索最后登录时间
     *
     * @param string $loginAt
     */
    public function loginAt(string $loginAt)
    {
        $this->query->where('login_at', $loginAt);
    }

    /**
     * 搜索最后登录IP
     *
     * @param string $loginIp
     */
    public function loginIp(string $loginIp)
    {
        $this->query->where('login_ip', $loginIp);
    }

    /**
     * 搜索禁用 0=否 1=禁用
     *
     * @param string $disabled
     */
    public function disabled(string $disabled)
    {
        $this->query->where('disabled', $disabled);
    }

    /**
     * 搜索消息通知数量
     *
     * @param int $notificationCount
     */
    public function notificationCount(int $notificationCount)
    {
        $this->query->where('notification_count', $notificationCount);
    }

    /**
     * 搜索Created_at
     *
     * @param string $createdAt
     */
    public function createdAt(string $createdAt)
    {
        $this->query->where('created_at', $createdAt);
    }

    /**
     * 搜索Updated_at
     *
     * @param string $updatedAt
     */
    public function updatedAt(string $updatedAt)
    {
        $this->query->where('updated_at', $updatedAt);
    }

}
