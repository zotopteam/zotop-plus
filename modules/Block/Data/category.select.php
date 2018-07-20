<?php
use \Modules\Block\Models\Category;

return Category::all()->pluck('name', 'id')->toArray();
