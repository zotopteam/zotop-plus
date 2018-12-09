<?php
use \Modules\Block\Models\Category;

return Category::sort()->get()->pluck('name', 'id')->toArray();
