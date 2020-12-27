<?php

namespace Modules\Editormd\Providers;

use App\Modules\Support\ServiceProvider;
use App\Support\Facades\Filter;
use App\Support\Facades\Form;
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
