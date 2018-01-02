<?php

return [
    'folder'      => [
        'text'  => trans('core::field.insert.from.folder',[$typename]),
        'icon'  => 'fa fa-folder',
        'href'  => route('core.file.select', ['root'=>'public/uploads', 'select'=>1] + $args),
    ],
];
