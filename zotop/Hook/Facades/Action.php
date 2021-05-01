<?php

namespace Zotop\Hook\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static listen(string $hook, $callback, int $priority = 50)
 * @method static fire(string $hook, ...$args)
 */
class Action extends Facade
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
