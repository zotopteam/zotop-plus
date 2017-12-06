<?php
use File;
use Module;

$types  = Module::data('core::file.types');
$options = [];
$options[''] = trans('media::file.type.all');
foreach ($types as $t => $n) {
    $options[$t] = $n;
}

return $options;
