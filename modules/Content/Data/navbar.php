<?php

use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;

// 导航
$navbar = [];

$navbar['index'] = [
    'text'  => trans('content::content.index'),
    'href'  => route('content.content.index'),
    'icon'  => 'fa fa-folder',
    'active' => Route::is('content.content.index'),
    'badge' => null,
];

// 计算每隔status的数据量
$counts = Content::select('status', DB::raw('COUNT(id) AS count'))
    ->groupBy('status')
    ->get()
    ->pluck('count', 'status')
    ->toArray();

// 所有状态都加入到侧边导航中
foreach (Content::status() as $status => $item) {
    $navbar[$status] = [
        'text'  => $item['name'],
        'href'  => route('content.content.status', $status),
        'icon'  => $item['icon'],
        'active' => Route::is('content.content.status') && Request::route('status') == $status,
        'badge' => Arr::get($counts, $status)
    ];
}

return $navbar;
