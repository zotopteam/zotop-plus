<?php

return [

    'range' => [
        'text'     => trans('developer::form.date.attribute.range'),
        'type'     => 'string',
        'required' => false,
        'example'  => '~',
    ],

    'min' => [
        'text'     => trans('developer::form.date.attribute.min'),
        'type'     => ['date', 'datetime', 'time'],
        'required' => false,
        'example'  => '2020-01-01',
    ],

    'max' => [
        'text'     => trans('developer::form.date.attribute.max'),
        'type'     => ['date', 'datetime', 'time'],
        'required' => false,
        'example'  => '2020-02-01',
    ],

    'theme' => [
        'text'     => trans('developer::form.date.attribute.theme'),
        'type'     => 'color',
        'required' => false,
        'example'  => '#0066cc',
    ],

    'pattern' => null,
];
