<?php

namespace Modules\Core\Hook;

use Illuminate\Support\Facades\Facade;

class ActionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
    	return 'hook.action';
    }
}