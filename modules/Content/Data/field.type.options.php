<?php
$types = collect(Module::data('content::field.types', $args));

$options = $types->filter(function($item, $key){
    return !empty($item['method']);
})->transform(function($item, $key) {
    return trans($item['name']);
});

return $options->toArray();
