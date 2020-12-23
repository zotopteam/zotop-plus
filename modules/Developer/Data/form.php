<?php

return [

    // 常规
    'common' => [
        'text'     => trans('developer::form.group.common'),
        'href'     => route('developer.form.group', ['group' => 'common']),
        'icon'     => 'fa fa-list-alt',
        'view'     => 'developer::form.group.common',
        'controls' => [
            'hidden'   => [],
            'text'     => [],
            'number'   => ['step' => 1],
            'password' => [],
            'email'    => [],
            'url'      => [],
            'tel'      => [],
            'range'    => [],
            'file'     => [],
            'color'    => [],
            'search'   => [],
            'textarea' => ['cols' => 30, 'rows' => 5],
        ],
    ],

    // 日期时间
    'dates'  => [
        'text'     => trans('developer::form.group.dates'),
        'href'     => route('developer.form.group', ['group' => 'dates']),
        'icon'     => 'fa fa-calendar-alt',
        'view'     => 'developer::form.group.dates',
        'controls' => [
            'date'     => [],
            'datetime' => [],
            'time'     => [],
            'month'    => [],
            'week'     => [],
        ],
    ],

];
