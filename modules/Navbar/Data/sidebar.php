<?php

$sidebar = [];

$sidebar[0] = [
    'text' => trans('navbar::navbar.default'),
    'href' => route('navbar.item.index', 0),
    'icon' => 'fa fa-compass',
];

foreach (\Modules\Navbar\Models\Navbar::enabled()->get() as $navbar) {
    $sidebar[$navbar->id] = [
        'text' => $navbar->title,
        'href' => route('navbar.item.index', $navbar->id),
        'icon' => 'fa fa-compass',
    ];
}

return $sidebar;
