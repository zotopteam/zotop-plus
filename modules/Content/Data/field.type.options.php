<?php
$types = collect(Module::data('content::field.types'));

$options = $types->filter(function($item, $key){
    return !empty($item['type']);
})->transform(function($item, $key) {
    return trans($item['name']);
});

return $options->toArray();
