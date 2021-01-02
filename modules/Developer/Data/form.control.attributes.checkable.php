<?php

return [

    'value' => [
        'text'     => trans('developer::form.control.attribute.value'),
        'type'     => ['string', 'number'],
        'required' => true,
        'example'  => '0',
    ],

    'checked' => [
        'text'     => trans('developer::form.control.attribute.checked'),
        'type'     => 'string',
        'required' => false,
        'value'    => 'checked',
    ],
];
