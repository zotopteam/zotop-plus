<?php

return [
    // 'uploaded'   => [
    //     'text'  => trans('core::field.insert.from.uploaded',[$typename]),
    //     'icon'  => 'fa fa-cloud',
    //     'href'  => route('core.file.select', ['from'=>'public/uploads'] + $args),
    // ],
    // 'libarary' => [
    //     'text'  => trans('core::field.insert.from.media',[$typename]),
    //     'icon'  => 'fa fa-database',
    //     'href'  => route('core.file.select', ['from'=>'public/uploads'] + $args),
    // ],
    'folder'      => [
        'text'  => trans('core::field.insert.from.folder',[$typename]),
        'icon'  => 'fa fa-folder',
        'href'  => route('core.file.select', ['root'=>'public/uploads', 'select'=>1] + $args),
    ],
];
