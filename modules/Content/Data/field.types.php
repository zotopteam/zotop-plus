<?php
$types  = [
    // 内置控件，method为空，不可选择并创建这些字段
    'content_title' => [
        'name'     => trans('content::field.type.title'),
        'view'     => ['content::field.settings.ranglength', 'content::field.settings.placeholder'],
        'method'   => '',
        'settings' => ['required' => 1, 'maxlength' => 255]
    ],
    'content_keywords' => [
        'name'     => trans('content::field.type.keywords'),
        'view'     => ['content::field.settings.placeholder'],
        'method'   => '',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'content_summary' => [
        'name'     => trans('content::field.type.summary'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.ranglength', 'content::field.settings.rows', 'content::field.settings.required'],
        'method'   => '',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'content_slug' => [
        'name'     => trans('content::field.type.slug'),
        'view'     => ['content::field.settings.required'],
        'method'   => '',
        'settings' => ['required' => 0, 'maxlength' => 255, 'unique' => 1]
    ],
    // 开放控件，可选择并创建这些类型
    'text' => [
        'name'     => trans('content::field.type.text'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.ranglength', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'textarea' => [
        'name'     => trans('content::field.type.textarea'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.ranglength', 'content::field.settings.rows', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'number' => [
        'name'   => trans('content::field.type.number'),
        'view'   => ['content::field.settings.placeholder', 'content::field.settings.length'],
        'method' => 'integer',
        'cast'   => 'integer',
    ],
    'editor' => [
        'name'     => trans('content::field.type.editor'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.rows', 'content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark'],
        'method'   => 'text',
        'settings' => ['required' => 0, 'rows' => 10, 'resize' => 1, 'watermark' => 1]
    ],
    'upload_image' => [
        'name'     => trans('content::field.type.image'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255, 'resize' => 0, 'watermark' => 0]
    ],
    'gallery' => [
        'name'     => trans('content::field.type.gallery'),
        'view'     => ['content::field.settings.required', 'content::field.settings.resize', 'content::field.settings.watermark', 'content::field.settings.fileslength'],
        'method'   => 'text',
        'cast'     => 'array',
        'settings' => ['required' => 0, 'min' => 0, 'max' => 999, 'resize' => 0, 'watermark' => 0]
    ],
    'upload_files' => [
        'name'     => trans('content::field.type.files'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'radiogroup' => [
        'name'     => trans('content::field.type.radiogroup'),
        'view'     => ['content::field.settings.options', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'checkboxgroup' => [
        'name'     => trans('content::field.type.checkboxgroup'),
        'view'     => ['content::field.settings.options', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
        'cast'     => 'array',
    ],
    'select' => [
        'name'     => trans('content::field.type.select'),
        'view'     => ['content::field.settings.options', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'date' => [
        'name'   => trans('content::field.type.date'),
        'view'   => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method' => 'date'
    ],
    'datetime' => [
        'name'   => trans('content::field.type.datetime'),
        'view'   => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method' => 'datetime'
    ],
    'email' => [
        'name'     => trans('content::field.type.email'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'link' => [
        'name'     => trans('content::field.type.link'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'url' => [
        'name'     => trans('content::field.type.url'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255]
    ],
    'view' => [
        'name'     => trans('content::field.type.view'),
        'view'     => ['content::field.settings.placeholder', 'content::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 100]
    ],
];

return $types;
