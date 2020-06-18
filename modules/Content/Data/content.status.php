<?php
return [
    'publish' => [
        'name' => trans('content::content.status.publish'),
        'text' => trans('content::content.publish'),
        'icon' => 'fa fa-check-circle',
    ],
    'trash' => [
        'name'  => trans('content::content.status.trash'),
        'text'  => trans('content::content.trash'),
        'icon'  => 'fa fa-trash-alt',
    ],
    'future' => [
        'name'  => trans('content::content.status.future'),
        'text'  => trans('content::content.future'),
        'icon'  => 'fa fa-clock',
    ],
    'draft' => [
        'name'  => trans('content::content.status.draft'),
        'text'  => trans('content::content.draft'),
        'icon'  => 'fa fa-life-ring',
    ],
    'pending' => [
        'name'  => trans('content::content.status.pending'),
        'text'  => trans('content::content.pending'),
        'icon'  => 'fa fa-hourglass-half',
    ],
    'unapproved' => [
        'name'  => trans('content::content.status.unapproved'),
        'text'  => trans('content::content.unapproved'),
        'icon'  => 'fa fa-exclamation-triangle',
    ],
];
