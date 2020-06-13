<?php

use Modules\Content\Models\Model;

$menu = Model::where('disabled', 0)->orderBy('sort', 'asc')->get()->filter(function ($item) use ($parent) {

    // 如果有父数据，则只显示父数据的可用模型
    if ($parent->models && is_array($parent->models)) {
        return array_get($parent->models, $item->id . '.enabled');
    }

    return true;
});

return $menu;
