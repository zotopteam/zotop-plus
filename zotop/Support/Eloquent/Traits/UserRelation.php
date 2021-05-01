<?php

namespace Zotop\Support\Eloquent\Traits;


use Illuminate\Support\Facades\Auth;

/**
 * 表含有user_id，保存时自动写入用户编号
 */
trait UserRelation
{
    /**
     * Boot the UserId trait for a model.
     *
     * @return void
     */
    public static function bootUserRelation()
    {
        static::updating(function ($model) {
            $model->user_id = Auth::User()->id ?? 0;
        });

        static::creating(function ($model) {
            $model->user_id = Auth::User()->id ?? 0;
        });
    }

    /**
     * 关联用户
     *
     * @return mixed
     * @author Chen Lei
     * @date 2020-11-07
     */
    public function user()
    {
        $guard = config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model = config("auth.providers.{$provider}.model");

        return $this->belongsTo($model)->withDefault();
    }
}
