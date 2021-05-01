<?php

namespace Zotop\Hook\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static listen(string $hook, $callback, int $priority = 50)
 * @method static fire(string $hook, $data, ...$args)
 */
class Filter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hook.filter';
    }
}
