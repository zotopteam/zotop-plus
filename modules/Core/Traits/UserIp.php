<?php

namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
            $model->user_ip = \Request::ip();
        });

        static::creating(function ($model) {
            $model->user_ip = \Request::ip();
        });
    }
}
