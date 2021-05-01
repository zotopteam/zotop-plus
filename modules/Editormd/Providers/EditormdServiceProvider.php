<?php

namespace Modules\Editormd\Providers;

use Zotop\Modules\Support\ServiceProvider;
use Zotop\Support\Facades\Filter;
use Zotop\Support\Facades\Form;
use Modules\Editormd\View\Controls\Code;
use Modules\Editormd\View\Controls\Markdown;


class EditormdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Filter::listen('developer::form.controls', 'Modules\Editormd\Hooks\Listener@controls');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Form::control('code', Code::class);
        Form::control('markdown', Markdown::class);
    }

}
