<?php
$types = [
    // 开放控件，可选择并创建这些类型
    'text'          => [
        'name'     => trans('navbar::field.type.text'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.ranglength', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'textarea'      => [
        'name'     => trans('navbar::field.type.textarea'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.ranglength', 'navbar::field.settings.rows', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'number'        => [
        'name'   => trans('navbar::field.type.number'),
        'view'   => ['navbar::field.settings.placeholder', 'navbar::field.settings.length'],
        'method' => 'integer',
        'cast'   => 'integer',
    ],
    'editor'        => [
        'name'     => trans('navbar::field.type.editor'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.rows', 'navbar::field.settings.required', 'navbar::field.settings.resize', 'navbar::field.settings.watermark'],
        'method'   => 'text',
        'settings' => ['required' => 0, 'rows' => 10, 'resize' => 1, 'watermark' => 1],
    ],
    'upload_image'  => [
        'name'     => trans('navbar::field.type.image'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required', 'navbar::field.settings.resize', 'navbar::field.settings.watermark'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255, 'resize' => 0, 'watermark' => 0],
    ],
    'gallery'       => [
        'name'     => trans('navbar::field.type.gallery'),
        'view'     => ['navbar::field.settings.required', 'navbar::field.settings.resize', 'navbar::field.settings.watermark', 'navbar::field.settings.fileslength'],
        'method'   => 'text',
        'cast'     => 'array',
        'settings' => ['required' => 0, 'min' => 0, 'max' => 999, 'resize' => 0, 'watermark' => 0],
    ],
    'upload_files'  => [
        'name'     => trans('navbar::field.type.files'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'radiogroup'    => [
        'name'     => trans('navbar::field.type.radiogroup'),
        'view'     => ['navbar::field.settings.options', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'checkboxgroup' => [
        'name'     => trans('navbar::field.type.checkboxgroup'),
        'view'     => ['navbar::field.settings.options', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
        'cast'     => 'array',
    ],
    'select'        => [
        'name'     => trans('navbar::field.type.select'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.options', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'date'          => [
        'name'   => trans('navbar::field.type.date'),
        'view'   => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method' => 'date',
    ],
    'datetime'      => [
        'name'   => trans('navbar::field.type.datetime'),
        'view'   => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method' => 'datetime',
    ],
    'email'         => [
        'name'     => trans('navbar::field.type.email'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'link'          => [
        'name'     => trans('navbar::field.type.link'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'url'           => [
        'name'     => trans('navbar::field.type.url'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 255],
    ],
    'view'          => [
        'name'     => trans('navbar::field.type.view'),
        'view'     => ['navbar::field.settings.placeholder', 'navbar::field.settings.required'],
        'method'   => 'string',
        'settings' => ['required' => 0, 'maxlength' => 100],
    ],
];

return $types;
