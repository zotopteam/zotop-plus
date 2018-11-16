<?php
use Modules\Content\Models\Model;

// 如果有父数据，则只显示父数据的可用模型
if ($parent->model_id) {

}

$menu = Model::where('disabled', 0)->orderBy('sort','asc')->get();

return $menu;
