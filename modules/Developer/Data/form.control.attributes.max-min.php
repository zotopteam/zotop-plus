<?php

return [

    'max' => [
        'text'     => trans('developer::form.control.attribute.max'),
        'type'     => ['integer', 'float'],
        'required' => false,
        'example'  => 20,
    ],

    'min' => [
        'text'     => trans('developer::form.control.attribute.min'),
        'type'     => ['integer', 'float'],
        'required' => false,
        'example'  => 5,
    ],
];
