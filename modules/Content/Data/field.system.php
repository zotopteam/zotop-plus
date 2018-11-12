<?php
$field = [
    ['label' =>trans('content::field.system.title'), 'name'=>'title', 'type'=>'title', 'nullable'=>0, 'unique'=>1, 'row'=>1, 'col'=>0],
    ['label' =>trans('content::field.system.image'), 'name'=>'image', 'type'=>'image', 'nullable'=>1, 'unique'=>0, 'row'=>2, 'col'=>0],
    ['label' =>trans('content::field.system.keywords'), 'name'=>'keywords', 'type'=>'keywords', 'nullable'=>1, 'unique'=>0, 'row'=>3, 'col'=>0],
    ['label' =>trans('content::field.system.summary'), 'name'=>'summary', 'type'=>'summary', 'nullable'=>1, 'unique'=>0, 'row'=>4, 'col'=>0],
    ['label' =>trans('content::field.system.template'), 'name'=>'template', 'type'=>'template', 'nullable'=>1, 'unique'=>0, 'row'=>5, 'col'=>0],
    ['label' =>trans('content::field.system.url'), 'name'=>'url', 'type'=>'url', 'nullable'=>1, 'unique'=>0, 'row'=>6, 'col'=>0],
];

return $field;
