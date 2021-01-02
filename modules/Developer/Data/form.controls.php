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
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.pattern',
            'developer::form.control.attributes.maxlength-minlength',
        ],
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.step',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.max-min',
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
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.pattern',
            'developer::form.control.attributes.maxlength-minlength',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'email' => [
        'text'       => trans('developer::form.control.email'),
        'href'       => route('developer.form.control', ['control' => 'email']),
        'icon'       => 'fa fa-envelope',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.maxlength-minlength',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'url' => [
        'text'       => trans('developer::form.control.url'),
        'href'       => route('developer.form.control', ['control' => 'url']),
        'icon'       => 'fa fa-link',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.maxlength-minlength',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'tel' => [
        'text'       => trans('developer::form.control.tel'),
        'href'       => route('developer.form.control', ['control' => 'tel']),
        'icon'       => 'fas fa-phone-square-alt',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.pattern',
            'developer::form.control.attributes.maxlength-minlength',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'range' => [
        'text'       => trans('developer::form.control.range'),
        'href'       => route('developer.form.control', ['control' => 'range']),
        'icon'       => 'fa fa-ruler-horizontal',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.step',
            'developer::form.control.attributes.max-min',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'file' => [
        'text'       => trans('developer::form.control.file'),
        'href'       => route('developer.form.control', ['control' => 'file']),
        'icon'       => 'fa fa-file',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
        ],
        'examples'   => 'developer::form.control.examples.standard',
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
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.pattern',
            'developer::form.control.attributes.maxlength-minlength',
        ],
        'examples'   => 'developer::form.control.examples.standard',
    ],

    'select' => [
        'text'       => trans('developer::form.control.select'),
        'href'       => route('developer.form.control', ['control' => 'select']),
        'icon'       => 'fa fa-caret-square-down',
        'examples'   => 'developer::form.control.examples.select',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.placeholder',
            'developer::form.select.attributes',
            'developer::form.control.attributes.required',
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.control.attributes.cols',
            'developer::form.control.attributes.rows',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.maxlength-minlength',
        ],
    ],

    'radio' => [
        'text'       => trans('developer::form.control.radio'),
        'href'       => route('developer.form.control', ['control' => 'radio']),
        'icon'       => 'far fa-circle',
        'examples'   => [
            'developer::form.control.examples.checkable',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.checkable',
        ],
    ],

    'radios' => [
        'text'       => trans('developer::form.control.radios'),
        'href'       => route('developer.form.control', ['control' => 'radios']),
        'icon'       => 'far fa-circle',
        'examples'   => [
            'developer::form.control.examples.radios',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.column',
        ],
    ],

    'radio-cards' => [
        'text'       => trans('developer::form.control.radio-cards'),
        'href'       => route('developer.form.control', ['control' => 'radio-cards']),
        'icon'       => 'far fa-circle',
        'examples'   => [
            'developer::form.control.examples.radios',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.column',
        ],
    ],

    'enable' => [
        'text'       => trans('developer::form.control.enable'),
        'href'       => route('developer.form.control', ['control' => 'enable']),
        'icon'       => 'far fa-circle',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => [
            'developer::form.control.attributes.standard',
        ],
    ],

    'bool' => [
        'text'       => trans('developer::form.control.bool'),
        'href'       => route('developer.form.control', ['control' => 'bool']),
        'icon'       => 'far fa-circle',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => [
            'developer::form.control.attributes.standard',
        ],
    ],

    'toggle' => [
        'text'       => trans('developer::form.control.toggle'),
        'href'       => route('developer.form.control', ['control' => 'toggle']),
        'icon'       => 'fa fa-toggle-on',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
        ],
    ],

    'checkbox' => [
        'text'       => trans('developer::form.control.checkbox'),
        'href'       => route('developer.form.control', ['control' => 'checkbox']),
        'icon'       => 'fa fa-check-square',
        'examples'   => [
            'developer::form.control.examples.checkable',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.checkable',
        ],
    ],

    'checkboxes' => [
        'text'       => trans('developer::form.control.checkboxes'),
        'href'       => route('developer.form.control', ['control' => 'checkboxes']),
        'icon'       => 'fa fa-check-square',
        'examples'   => [
            'developer::form.control.examples.checkboxes',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
            'developer::form.control.attributes.value.mixed',
            'developer::form.control.attributes.column',
        ],
    ],

    // 'checkbox-cards' => [
    //     'text'       => trans('developer::form.control.checkbox-cards'),
    //     'href'       => route('developer.form.control', ['control' => 'checkbox-cards']),
    //     'icon'       => 'fa fa-check-square',
    //     'examples'   => [
    //         'developer::form.control.examples.checkboxes',
    //     ],
    //     'attributes' => [
    //         'developer::form.control.attributes.standard',
    //         'developer::form.control.attributes.required',
    //         'developer::form.control.attributes.value.mixed',
    //         'developer::form.control.attributes.column',
    //     ],
    // ],

    'button' => [
        'text'       => trans('developer::form.control.button'),
        'href'       => route('developer.form.control', ['control' => 'button']),
        'icon'       => 'fa fa-save',
        'examples'   => [
            'developer::form.control.examples.buttons',
        ],
        'attributes' => [
            'developer::form.control.attributes.standard',
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.date.attributes',
            'developer::form.control.attributes.required',
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
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
            'developer::form.control.attributes.placeholder',
            'developer::form.control.attributes.autocomplete',
            'developer::form.date.attributes',
            'developer::form.control.attributes.required',
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
            'developer::form.control.attributes.required',
        ],
    ],

    'week' => [
        'text'       => trans('developer::form.control.week'),
        'href'       => route('developer.form.control', ['control' => 'week']),
        'icon'       => 'fa fa-calendar-alt',
        'examples'   => 'developer::form.control.examples.standard',
        'attributes' => [
            'developer::form.control.attributes.standard',
            'developer::form.control.attributes.required',
        ],
    ],

];
