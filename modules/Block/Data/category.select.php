<?php
use \Modules\Block\Models\Category;

return Category::sorted()->get()->pluck('name', 'id')->toArray();
