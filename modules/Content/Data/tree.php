<?php
use Modules\Content\Models\Content;

$tree = Content::select('id','parent_id','title')->where('model_id','category')->orderBy('sort','desc')->get()->map(function($item, $key){
    $item->key    = $item->id;
    $item->href   = route('content.content.index',[$item->id]);
    $item->folder = true;
    return $item;
})->toArray();

$tree = [
    [
        'folder'    => true,
        'key'       => 0,
        'icon'      => 'fas fa-home text-primary',
        'title'     => trans('content::content.root'),
        'href'      => route('content.content.index',[0]),
        'children'  => array_nest($tree)
    ]
];

return $tree;
