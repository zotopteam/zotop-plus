<?php
namespace Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

        // static::addGlobalScope('user', function (Builder $builder) {
        //     $builder->with('user');
        // });            
    }

    public function user()
    {
        return $this->belongsTo('Modules\Core\Models\User')->withDefault();
    }    
}
