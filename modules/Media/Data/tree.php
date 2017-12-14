<?php
use Modules\Media\Models\Folder;

$tree = Folder::select('id','parent_id','name as title')->orderBy('sort','asc')->get()->map(function($item, $key){
    $item->key    = $item->id;
    $item->href   = route('media.index',[$item->id]);
    $item->folder = true;
    return $item;
})->toArray();

$tree = [
    [
        'folder'    => true,
        'key'       => 0,
        'icon'      => 'fas fa-home text-primary',
        'title'     => trans('media::media.root'),
        'href'      => route('media.index',[0]),
        'children'  => array_nest($tree)
    ],
    // [
    //     'folder'    => true,
    //     'key'       => -1,
    //     'icon'      => 'fas fa-trash text-primary',
    //     'title'     => trans('media::media.trash'),
    //     'href'      => route('media.trash'),    
    // ]
];

return $tree;
