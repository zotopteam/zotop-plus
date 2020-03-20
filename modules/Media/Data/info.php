<?php
use Modules\Media\Models\Media;
use Modules\Core\Support\Facades\Format;

$info = [
    'filecounts' => [
        'icon'  => 'fas fa-home text-primary',
        'title' => trans('core::file.count'),
        'badge' => Media::whereNotIn('type', ['folder'])->count(),
    ],
    'filespace' => [
        'icon'  => 'fas fa-trash text-primary',
        'title' => trans('core::file.space'),
        'badge' => size_format(Media::whereNotIn('type', ['folder'])->sum('size')),
    ]
];

return $info;
