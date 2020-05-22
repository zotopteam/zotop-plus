<?php

$typename = trans('core::file.type.'.$type);

return [
    'dir'      => [
        'text'  => trans('core::field.insert.from.dir',[$typename]),
        'icon'  => 'fa fa-folder',
        'href'  => route('core.file.select', ['root'=>'public', 'select'=>1] + $args),
    ],
];
