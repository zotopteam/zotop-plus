<?php
return [
    'bind' => [
        'text'     => trans('developer::form.attribute.bind'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'test',
    ],

    'method' => [
        'text'     => trans('developer::form.attribute.method'),
        'type'     => 'string',
        'required' => false,
        'value'    => ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'],
    ],

    'route' => [
        'text'     => trans('developer::form.attribute.route'),
        'type'     => ['string', 'array'],
        'required' => false,
        'examples' => ['developer::form.index', "['developer::form.update', ['id'=>1]]"],
    ],

    'class' => [
        'text'     => trans('developer::form.attribute.class'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'm-2',
    ],

    'style' => [
        'text'     => trans('developer::form.attribute.style'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'margin:2rem;',
    ],

];

