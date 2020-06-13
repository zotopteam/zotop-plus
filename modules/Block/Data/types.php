<?php
$types = [];

$types['list'] = [
    'type'   => 'list',
    'icon'   => 'far fa-list-alt',
    'name'   => trans('block::type.list'),
    'help'   => trans('block::type.list.help'),
    'create' => 'block::block.create_list',
    'edit'   => 'block::block.edit_list',
    'data'   => 'block::block.data_list',
    'view'   => 'block::list',
    'fields' => [
        ['show' => 2, 'label' => trans('block::type.list.fields.title'), 'type' => 'title', 'name' => 'title', 'minlength' => 1, 'maxlength' => 50, 'required' => 'required'],
        ['show' => 0, 'label' => trans('block::type.list.fields.url'), 'type' => 'link', 'name' => 'url', 'required' => 'required'],
        ['show' => 0, 'label' => trans('block::type.list.fields.image'), 'type' => 'upload_image', 'name' => 'image', 'required' => 'required', 'resize' => ['enabled' => 0, 'width' => '', 'height' => ''], 'watermark' => 0],
        ['show' => 0, 'label' => trans('block::type.list.fields.description'), 'type' => 'textarea', 'name' => 'description', 'required' => 'required', 'minlength' => 0, 'maxlength' => 255, 'rows' => 3],
        ['show' => 0, 'label' => trans('block::type.list.fields.time'), 'type' => 'datetime', 'name' => 'time', 'required' => 'required'],
    ]
];

$types['html'] = [
    'type' => 'html',
    'icon' => 'far fa-newspaper',
    'name' => trans('block::type.html'),
    'help' => trans('block::type.html.help'),
];

$types['text'] = [
    'type' => 'text',
    'icon' => 'fas fa-align-justify',
    'name' => trans('block::type.text'),
    'help' => trans('block::type.text.help'),
];

$types['code'] = [
    'type' => 'code',
    'icon' => 'fa fa-code',
    'name' => trans('block::type.code'),
    'help' => trans('block::type.code.help'),
];


return $types;
