<?php

namespace Modules\Translator\Providers;

use App\Modules\Support\ServiceProvider;
use App\Support\Facades\Filter;
use App\Support\Facades\Form;
use Modules\Translator\View\Controls\Slug;
use Modules\Translator\View\Controls\Translate;

class TranslatorServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Form::control(Translate::class, 'translate');
        Form::control(Slug::class, 'slug');
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Form::control(Slug::class, 'content-slug');

        Filter::listen('developer::form.controls', 'Modules\Translator\Hooks\Listener@controls');
    }

}
