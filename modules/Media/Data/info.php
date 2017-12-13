<?php
use Modules\Media\Models\File;
use Modules\Core\Support\Facades\Format;

$info = [
    'filecounts' => [
        'icon'  => 'fas fa-home text-primary',
        'title' => trans('media::file.count'),
        'badge' => File::count(),
    ],
    'filespace' => [
        'icon'  => 'fas fa-trash text-primary',
        'title' => trans('media::file.space'),
        'badge' => Format::size(File::sum('size')),
    ]
];

return $info;
