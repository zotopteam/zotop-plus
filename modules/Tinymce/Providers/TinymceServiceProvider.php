<?php

namespace Modules\Tinymce\Providers;

use Zotop\Modules\Support\ServiceProvider;
use Zotop\Support\Facades\Filter;
use Zotop\Support\Facades\Form;
use Modules\Tinymce\View\Controls\Editor;

class TinymceServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Filter::listen('developer::form.controls', 'Modules\Tinymce\Hooks\Listener@controls');

        Form::control('tinymce', Editor::class);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Form::control('editor', Editor::class);
    }

}
