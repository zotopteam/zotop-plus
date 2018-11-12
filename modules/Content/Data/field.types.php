<?php
$types  = [
    // 内置控件，没有database_type 不可选择并创建这些类型
    'title'         => ['name' =>trans('content::field.type.title'), 'view'=>'content::field.settings.ranglength', 'database_type'=>''],
    'keywords'      => ['name' =>trans('content::field.type.keywords'), 'view'=>'', 'database_type'=>''],
    'summary'       => ['name' =>trans('content::field.type.summary'), 'view'=>'content::field.settings.textarea', 'database_type'=>''],

    // 开放控件，可选择并创建这些类型
    'template'      => ['name' =>trans('content::field.type.template'), 'view'=>'', 'database_type'=>'varchar'],
    'url'           => ['name' =>trans('content::field.type.url'), 'view'=>'', 'database_type'=>'varchar'],
    'text'          => ['name' =>trans('content::field.type.text'), 'view'=>'content::field.settings.text', 'database_type'=>'varchar'],
    'textarea'      => ['name' =>trans('content::field.type.textarea'), 'view'=>'content::field.settings.textarea', 'database_type'=>'varchar'],
    'number'        => ['name' =>trans('content::field.type.number'), 'view'=>'content::field.settings.number', 'database_type'=>'int'],
    'radiogroup'    => ['name' =>trans('content::field.type.radiogroup'), 'view'=>'content::field.settings.radiogroup', 'database_type'=>'varchar'],
    'checkboxgroup' => ['name' =>trans('content::field.type.radiogroup'), 'view'=>'content::field.settings.checkboxgroup', 'database_type'=>'varchar'],
    'select'        => ['name' =>trans('content::field.type.select'), 'view'=>'content::field.settings.select', 'database_type'=>'varchar'],
    'editor'        => ['name' =>trans('content::field.type.editor'), 'view'=>'content::field.settings.editor', 'database_type'=>'text'],
    'image'         => ['name' =>trans('content::field.type.image'), 'view'=>'content::field.settings.image', 'database_type'=>'varchar'],
    'gallery'       => ['name' =>trans('content::field.type.gallery'), 'view'=>'content::field.settings.gallery', 'database_type'=>'text'],
    'files'         => ['name' =>trans('content::field.type.files'), 'view'=>'content::field.settings.files', 'database_type'=>'varchar'],
    'date'          => ['name' =>trans('content::field.type.date'), 'view'=>'content::field.settings.date', 'database_type'=>'date'],
    'datetime'      => ['name' =>trans('content::field.type.datetime'), 'view'=>'content::field.settings.datetime', 'database_type'=>'datetime'],
    'email'         => ['name' =>trans('content::field.type.email'), 'view'=>'content::field.settings.email', 'database_type'=>'varchar'],
];

return $types;
