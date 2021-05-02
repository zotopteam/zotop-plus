<?php

namespace Zotop\View\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static control($control, $class)
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
