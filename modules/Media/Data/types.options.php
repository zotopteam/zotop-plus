<?php

$types  = app('modules')->data('core::file.types');
$options = [];
$options[''] = trans('core::file.type.all');
foreach ($types as $t => $n) {
    $options[$t] = $n;
}

return $options;
