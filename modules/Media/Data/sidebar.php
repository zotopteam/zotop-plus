<?php

$sidebar = [];

$sidebar['index'] = [
    'text' => trans('core::file.type.all'),
    'href' => route('media.index'),
    'icon' => 'fa fa-folder',
];

foreach (Module::data('core::file.types') as $type => $name) {
    $sidebar[$type] = [
        'text' => $name,
        'href' => route('media.type', [$type]),
        'icon' => app('files')->icon($type),
    ];
}

return $sidebar;
