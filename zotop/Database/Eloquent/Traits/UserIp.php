<?php

namespace Zotop\Database\Eloquent\Traits;

use Illuminate\Support\Facades\Request;

/**
 * 表含有user_ip，保存时自动写入IP地址
 */
trait UserIp
{
    /**
     * Boot the UserIp trait for a model.
     *
     * @return void
     */
    public static function bootUserIp()
    {
        static::updating(function ($model) {
            $model->user_ip = Request::ip();
        });

        static::creating(function ($model) {
            $model->user_ip = Request::ip();
        });
    }
}
