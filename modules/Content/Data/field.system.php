<?php

// 系统字段
$field = [
    ['label' => trans('content::field.system.title'), 'name' => 'title', 'type' => 'content_title'],
    ['label' => trans('content::field.system.slug'), 'name' => 'slug', 'type' => 'content_slug'],
    ['label' => trans('content::field.system.image'), 'name' => 'image', 'type' => 'upload_image'],
    ['label' => trans('content::field.system.keywords'), 'name' => 'keywords', 'type' => 'content_keywords'],
    ['label' => trans('content::field.system.summary'), 'name' => 'summary', 'type' => 'content_summary'],
    ['label' => trans('content::field.system.view'), 'name' => 'view', 'type' => 'view'],
    ['label' => trans('content::field.system.link'), 'name' => 'link', 'type' => 'link'],
];

return $field;
