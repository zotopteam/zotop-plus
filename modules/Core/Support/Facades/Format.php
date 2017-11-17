<?php
namespace Modules\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Format extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'format';
    }
}
