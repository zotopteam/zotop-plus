<?php

$typename = $typename ?? trans('core::file.type.' . $type);

return [
    'dir'      => [
        'text'  => trans('core::field.insert.from.dir', [$typename]),
        'icon'  => 'fa fa-folder',
        'href'  => route('core.file.select', $args),
    ],
];
