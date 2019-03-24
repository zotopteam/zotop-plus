<?php
$field = [
    ['label'=>trans('content::field.system.title'), 'name'=>'title', 'type'=>'title'],
    ['label'=>trans('content::field.system.slug'), 'name'=>'slug', 'type'=>'slug'],
    ['label'=>trans('content::field.system.image'), 'name'=>'image', 'type'=>'upload_image'],
    ['label'=>trans('content::field.system.keywords'), 'name'=>'keywords', 'type'=>'keywords'],
    ['label'=>trans('content::field.system.summary'), 'name'=>'summary', 'type'=>'summary'],
    ['label'=>trans('content::field.system.view'), 'name'=>'view', 'type'=>'view'],
    ['label'=>trans('content::field.system.link'), 'name'=>'link', 'type'=>'link'],
];

return $field;
