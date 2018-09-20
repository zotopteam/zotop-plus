<?php

return [
    'dir'      => [
        'text'  => trans('core::field.insert.from.dir',[$typename]),
        'icon'  => 'fa fa-folder',
        'href'  => route('core.file.select', ['root'=>'public/uploads', 'select'=>1] + $args),
    ],
];
