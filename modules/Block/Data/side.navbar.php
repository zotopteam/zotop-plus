<?php
use Modules\Block\Models\Category;

$navbar = [];

$categories = Category::sort()->get();

// 获取route中的参数 category_id
$category_id = request()->route('category_id');

// 如果获取不到，取出第一个分类的编号，自动定位到第一个分类上，和控制器里面保持一致
if (empty($category_id) && $categories) {
    $category_id = $categories->first()->id;
}

foreach ($categories as $category) {
    $navbar['category_'.$category->id] = [
        'text'  => $category->name,
        'href'  => route('block.index', $category->id),
        'icon'  => 'fa fa-folder',
        'class' => ($category->id == $category_id) ? 'active' : '',
    ];
}

return $navbar;
