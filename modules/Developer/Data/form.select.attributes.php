<?php

return [

    'options' => [
        'text'     => trans('developer::form.select.attributes.options'),
        'type'     => 'array',
        'required' => true,
        'example'  => [
            '1' => 'aaa',
            '2' => 'bbb',
        ],
    ],

    'multiple' => [
        'text'     => trans('developer::form.select.attributes.multiple'),
        'type'     => 'boolean',
        'required' => false,
        'example'  => true,
    ],

    'pattern' => null,
];
