<?php
return [
    'publish' => [
        'name'  => trans('content::content.status.publish'),
        'icon'  => 'fa fa-check-circle text-success',
    ],
    'draft' => [
        'name'  => trans('content::content.status.draft'),
        'icon'  => 'fa fa-life-ring text-primary',
    ],
    'pending' => [
        'name'  => trans('content::content.status.pending'),
        'icon'  => 'fa fa-hourglass-half text-info',
    ],
    'fail' => [
        'name'  => trans('content::content.status.fail'),
        'icon'  => 'fa fa-exclamation-triangle text-danger',
    ],    
    'trash' => [
        'name'  => trans('content::content.status.trash'),
        'icon'  => 'fa fa-trash-alt text-muted',
    ],
    'future' => [
        'name'  => trans('content::content.status.future'),
        'icon'  => 'fa fa-clock text-warning',
    ],                      
];
