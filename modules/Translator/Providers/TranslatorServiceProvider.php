<?php

namespace Modules\Translator\Providers;

use App\Modules\Support\ServiceProvider;
use App\Support\Facades\Form;
use Modules\Translator\View\Controls\Slug;
use Modules\Translator\View\Controls\Translate;

class TranslatorServiceProvider extends ServiceProvider
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
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Form::control(Translate::class, 'translate');
        Form::control(Slug::class, 'slug');
        Form::control(Slug::class, 'content-slug');
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
