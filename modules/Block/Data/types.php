<?php
$types = [];

$path = module_path('block').'/Resources/type/';

$types['list'] = [
    'type' => 'list',
    'image' => preview($path.'list.png', 150, 90),
    'title' => trans('block::type.list'),
];

$types['html'] = [
    'type' => 'html',
    'image' => preview($path.'html.png', 150, 90),
    'title' => trans('block::type.html'),
];

$types['text'] = [
    'type' => 'text',
    'image' => preview($path.'text.png', 150, 90),
    'title' => trans('block::type.text'),
];


return $types;
