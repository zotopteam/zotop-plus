<?php
use Modules\Content\Models\Field;

$types = collect(Field::types($model_id));

$options = $types->filter(function($item, $key){
    return !empty($item['method']);
})->transform(function($item, $key) {
    return trans($item['name']);
});

return $options->toArray();
