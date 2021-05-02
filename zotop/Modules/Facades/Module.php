<?php

namespace Zotop\Modules\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static findOrFail($name)
 * @method static all()
 * @method static path(string $string)
 */
class Module extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'modules';
    }
}
