<?php

namespace Modules\Tinymce\Providers;

use App\Modules\Support\ServiceProvider;
use App\Support\Facades\Form;
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
