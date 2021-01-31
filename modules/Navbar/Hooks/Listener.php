<?php

namespace Modules\Navbar\Hooks;

class Listener
{
    /**
     * Hook the start
     *
     * @param array $start
     * @return array
     */
    public function start($start)
    {
        if (allow('navbar.index')) {
            $start['navbar'] = [
                'text' => trans('navbar::module.title'),
                'href' => route('navbar.navbar.index'),
                'icon' => 'fa fa-compass bg-info text-white',
                'tips' => trans('navbar::module.description'),
            ];
        }

        return $start;
    }

    /**
     * Hook the navbar
     *
     * @param array $navbar
     * @return array
     */
    public function navbar($navbar)
    {
        return $navbar;
    }
}
