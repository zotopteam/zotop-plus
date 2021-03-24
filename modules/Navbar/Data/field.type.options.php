<?php

use Modules\Navbar\Models\Field;

return Field::types()->transform(function ($item, $key) {
    return $item['name'];
})->toArray();
