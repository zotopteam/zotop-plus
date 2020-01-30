<?php

$types = [];

foreach (array_keys(config('modules.types')) as $type) {
    $types[$type] = trans("developer::theme.type.{$type}");
}

return $types;
