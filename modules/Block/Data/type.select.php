<?php
$return = [];
$types = \Module::data('block::types');

foreach ($types as $value=>$type) {
    
    $image = $type['image'];
    $title = $type['title'];

    $return[$value] = [$image, $title];
}

return $return;
