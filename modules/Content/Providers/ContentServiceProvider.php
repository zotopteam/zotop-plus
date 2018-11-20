<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\Content;
use Modules\Content\Models\Model;


class ContentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        // $models = Model::where('disabled', 0)->get()->each(function($model) {
        //     Content::macro($model->id, function() {
        //         $instance = app($this->model->model)->setTable($this->model->table)->setCasts($this->model->casts)->fillable($this->model->fillable);
        //         return $this->newHasOne($instance->newQuery(), $this, $instance->getTable().'.id', 'id');
        //     });
        // });
    }    

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
