<?php

return [

    'hidden' => [
        'text'       => trans('developer::form.control.hidden'),
        'href'       => route('developer.form.control', ['control' => 'hidden']),
        'icon'       => 'fa fa-eye-slash',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'text' => [
        'text'       => trans('developer::form.control.text'),
        'href'       => route('developer.form.control', ['control' => 'text']),
        'icon'       => 'fa fa-ellipsis-h',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.value.string',
        ],
    ],

    'number' => [
        'text'       => trans('developer::form.control.number'),
        'href'       => route('developer.form.control', ['control' => 'number']),
        'icon'       => 'fa fa-dice-four',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.number.attributes',
        ],
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.value.number',
        ],
    ],

    'password' => [
        'text'       => trans('developer::form.control.password'),
        'href'       => route('developer.form.control', ['control' => 'password']),
        'icon'       => 'fa fa-key',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'email' => [
        'text'       => trans('developer::form.control.email'),
        'href'       => route('developer.form.control', ['control' => 'email']),
        'icon'       => 'fa fa-envelope',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'url' => [
        'text'       => trans('developer::form.control.url'),
        'href'       => route('developer.form.control', ['control' => 'url']),
        'icon'       => 'fa fa-link',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'tel' => [
        'text'       => trans('developer::form.control.tel'),
        'href'       => route('developer.form.control', ['control' => 'tel']),
        'icon'       => 'fas fa-phone-square-alt',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'range' => [
        'text'       => trans('developer::form.control.range'),
        'href'       => route('developer.form.control', ['control' => 'range']),
        'icon'       => 'fa fa-ruler-horizontal',
        'attributes' => 'developer::form.control.attributes.standard',
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'file' => [
        'text'       => trans('developer::form.control.file'),
        'href'       => route('developer.form.control', ['control' => 'file']),
        'icon'       => 'fa fa-file',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => 'developer::form.control.attributes.standard',
    ],

    'color' => [
        'text'       => trans('developer::form.control.color'),
        'href'       => route('developer.form.control', ['control' => 'color']),
        'icon'       => 'fas fa-palette',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => 'developer::form.control.attributes.standard',
    ],

    'search' => [
        'text'       => trans('developer::form.control.search'),
        'href'       => route('developer.form.control', ['control' => 'search']),
        'icon'       => 'fa fa-search',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => 'developer::form.control.attributes.standard',
    ],

    'select' => [
        'text'       => trans('developer::form.control.select'),
        'href'       => route('developer.form.control', ['control' => 'select']),
        'icon'       => 'fa fa-caret-square-down',
        'examples'   => 'developer::form.control.examples.select',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.select.attributes',
        ],
    ],

    'textarea' => [
        'text'       => trans('developer::form.control.textarea'),
        'href'       => route('developer.form.control', ['control' => 'textarea']),
        'icon'       => 'fa fa-align-left',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.textarea',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.textarea.attributes',
        ],
    ],

    'date' => [
        'text'       => trans('developer::form.control.date'),
        'href'       => route('developer.form.control', ['control' => 'date']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.date',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.date.attributes',
        ],
    ],

    'datetime' => [
        'text'       => trans('developer::form.control.datetime'),
        'href'       => route('developer.form.control', ['control' => 'datetime']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.date',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.date.attributes',
        ],
    ],

    'time' => [
        'text'       => trans('developer::form.control.time'),
        'href'       => route('developer.form.control', ['control' => 'time']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.date',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.date.attributes',
        ],
    ],

    'month' => [
        'text'       => trans('developer::form.control.month'),
        'href'       => route('developer.form.control', ['control' => 'month']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.date',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.date.attributes',
        ],
    ],

    'year' => [
        'text'       => trans('developer::form.control.year'),
        'href'       => route('developer.form.control', ['control' => 'year']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => [
            'developer::form.control.examples.standard',
            'developer::form.control.examples.date',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.date.attributes',
        ],
    ],

    'week' => [
        'text'       => trans('developer::form.control.week'),
        'href'       => route('developer.form.control', ['control' => 'week']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => 'developer::form.control.attributes.standard',
    ],
];
