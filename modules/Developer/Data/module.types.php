<?php

$types = [];

foreach (config('modules.types') as $k => $v) {
    $types[$k] = trans("developer::module.types.{$k}");
}

return $types;
