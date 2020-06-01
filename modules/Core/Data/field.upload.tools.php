<?php

// return [
//     // 'dir'      => [
//     //     'text'  => trans('core::field.insert.from.dir'),
//     //     'icon'  => 'fa fa-folder',
//     //     'href'  => route('core.file.select', $args),
//     // ],
// ];

$tools = [];

foreach (['public'] as $disk) {
    $tools["disk-{$disk}"] = [
        'text'   => trans("core::storage.disk.{$disk}.title"),
        'icon'   => 'fa fa-hdd',
        'href'   => route('core.storage.file.select', ['disk' => $disk] + $args),
        'active' => Route::is('core.storage.file.select') && Route::current()->parameter('disk') == $disk,
    ];
}

return $tools;
