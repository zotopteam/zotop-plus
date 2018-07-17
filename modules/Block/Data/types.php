<?php
$types = [];

$types['list'] = [
    'type'     => 'list',
    'icon'     => 'far fa-list-alt',
    'title'    => trans('block::type.list'),
    'help'     => trans('block::type.list.help'),
    'create'   => 'block::block.create_list',
    'edit'     => 'block::block.edit_list',
    'template' => 'block::list',
];

$types['html'] = [
    'type' => 'html',
    'icon' => 'far fa-newspaper',
    'title' => trans('block::type.html'),
    'help'  => trans('block::type.html.help'),
];

$types['text'] = [
    'type' => 'text',
    'icon' => 'fas fa-align-justify',
    'title' => trans('block::type.text'),
    'help'  => trans('block::type.text.help'),
];

$types['code'] = [
    'type' => 'code',
    'icon' => 'fa fa-code',
    'title' => trans('block::type.code'),
    'help'  => trans('block::type.code.help'),
];


return $types;
