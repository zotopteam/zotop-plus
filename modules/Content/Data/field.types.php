<?php
$types  = [
    // 内置控件，没有type 不可选择并创建这些类型
    'title' => [
        'name'     => trans('content::field.type.title'),
        'view'     => ['content::field.settings.ranglength'],
        'type'     => '',
        'settings' => ['maxlength'=>255, 'required'=>1]
    ],
    'keywords' => [
        'name' => trans('content::field.type.keywords'),
        'view' => '',
        'type' => '',
        'settings' => ['maxlength'=>100]
    ],
    'summary' => [
        'name'     => trans('content::field.type.summary'),
        'view'     => ['content::field.settings.ranglength','content::field.settings.rows','content::field.settings.required'],
        'type'     => '',
        'settings' => ['maxlength'=>100]
    ],

    // 开放控件，可选择并创建这些类型
    'text' => [
        'name'     => trans('content::field.type.text'),
        'view'     => ['content::field.settings.ranglength','content::field.settings.required'],
        'type'     => 'varchar',
        'settings' => ['maxlength'=>255]
    ],
    'textarea' => [
        'name' => trans('content::field.type.textarea'),
        'view' => ['content::field.settings.ranglength','content::field.settings.rows','content::field.settings.required'],
        'type' => 'varchar'
    ],
    'number' => [
        'name' => trans('content::field.type.number'),
        'view' => ['content::field.settings.length'],
        'type' => 'int'
    ],
    'editor' => [
        'name' => trans('content::field.type.editor'),
        'view' => ['content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark'],
        'type' => 'text'
    ],
    'image' => [
        'name'     => trans('content::field.type.image'),
        'view'     => ['content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark'],
        'type'     => 'varchar',
        'settings' => ['maxlength'=>255,'resize'=>0,'watermark'=>0]
    ],
    'gallery' => [
        'name'     => trans('content::field.type.gallery'),
        'view'     => ['content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark', 'content::field.settings.fileslength'],
        'type'     => 'text',
        'settings' => ['min'=>0,'max'=>999,'resize'=>0,'watermark'=>0]
    ],
    'files' => [
        'name' => trans('content::field.type.files'),
        'view' => ['content::field.settings.required'],
        'type' => 'varchar'
    ],
    'radiogroup' => [
        'name' => trans('content::field.type.radiogroup'),
        'view' => ['content::field.settings.options','content::field.settings.required'],
        'type' => 'varchar'
    ],
    'checkboxgroup' => [
        'name' => trans('content::field.type.radiogroup'),
        'view' => ['content::field.settings.options','content::field.settings.required'],
        'type' => 'varchar'
    ],
    'select' => [
        'name' => trans('content::field.type.select'),
        'view' => ['content::field.settings.options','content::field.settings.required'],
        'type' => 'varchar',
        //'settings' => ['options'=>[['text'=>'yes','value'=>'222'],['text'=>'no','value'=>'111']]]
    ],    
    'date' => [
        'name' => trans('content::field.type.date'),
        'view' => ['content::field.settings.required'],
        'type' => 'date'
    ],
    'datetime' => [
        'name' => trans('content::field.type.datetime'),
        'view' => ['content::field.settings.required'],
        'type' => 'datetime'
    ],
    'email' => [
        'name' => trans('content::field.type.email'),
        'view' => ['content::field.settings.required'],
        'type' => 'varchar'
    ],
    'url' => [
        'name'     => trans('content::field.type.url'),
        'view'     => ['content::field.settings.required'],
        'type'     => 'varchar',
        'settings' => ['maxlength'=>255]
    ],    
    'template' => [
        'name'     => trans('content::field.type.template'),
        'view'     => ['content::field.settings.required'],
        'type'     => 'varchar',
        'settings' => ['maxlength'=>100]
    ],
];

return $types;
