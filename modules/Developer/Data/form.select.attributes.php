<?php

return [

    'options' => [
        'text'     => trans('developer::form.select.attribute.options'),
        'type'     => 'array',
        'required' => true,
        'example'  => [
            '1' => 'aaa',
            '2' => 'bbb',
        ],
    ],

    'multiple' => [
        'text'     => trans('developer::form.select.attribute.multiple'),
        'type'     => 'boolean',
        'required' => false,
        'example'  => true,
    ],
];
