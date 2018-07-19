<?php
$types = [];

$types['list'] = [
    'type'     => 'list',
    'icon'     => 'far fa-list-alt',
    'name'     => trans('block::type.list'),
    'help'     => trans('block::type.list.help'),
    'create'   => 'block::block.create_list',
    'edit'     => 'block::block.edit_list',
    'data'     => 'block::block.data_list',
    'template' => 'block::list',
    'fields'   => [
        'title'         => ['show'=>2,'label'=>trans('标题'),'type'=>'title','name'=>'title','minlength'=>1,'maxlength'=>50, 'required'=>'required'],
        'url'           => ['show'=>0,'label'=>trans('链接'),'type'=>'link','name'=>'url','required'=>'required'],
        'image'         => ['show'=>0,'label'=>trans('图片'),'type'=>'image','name'=>'image','required'=>'required','image_resize'=>1,'image_width'=>'','image_height'=>'','watermark'=>0],
        //'description'   => ['show'=>0,'label'=>trans('摘要'),'type'=>'textarea','name'=>'description','required'=>'required','minlength'=>0,'maxlength'=>255],
        //'time'          => ['show'=>0,'label'=>trans('日期'),'type'=>'datetime','name'=>'time','required'=>'required'],
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
