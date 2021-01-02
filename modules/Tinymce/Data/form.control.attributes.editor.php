<?php

return [

    'width' => [
        'text'     => trans('tinymce::form.editor.attributes.width'),
        'type'     => ['integer', 'string'],
        'required' => false,
        'example'  => '50%',
    ],

    'height' => [
        'text'     => trans('tinymce::form.editor.attributes.height'),
        'type'     => 'intger',
        'required' => false,
        'example'  => 300,
    ],

    'options' => [
        'text'     => trans('tinymce::form.editor.attributes.options'),
        'type'     => ['string', 'array'],
        'required' => false,
        'value'    => ['default', 'full', 'standard', 'mini'],
    ],

    'toolbar' => [
        'text'     => trans('tinymce::form.editor.attributes.toolbar'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'bold italic underline strikethrough forecolor',
    ],

    'plugins' => [
        'text'     => trans('tinymce::form.editor.attributes.plugins'),
        'type'     => 'string',
        'required' => false,
        'example'  => 'preview paste',
    ],

    'resize' => [
        'text'     => trans('tinymce::form.editor.attributes.resize'),
        'type'     => 'bool',
        'required' => false,
        'example'  => 'true',
    ],
];
