<?php

$types  = app('modules')->data('core::file.types');
$navbar = [];

foreach ($types as $t => $n) {
    $navbar[$t] = [
        'text'   => $n,
        'href'   => route('media.index',[$folder_id, $t]),
        'icon'   => app('files')->icon($t),
        'active' => app('router')->active('media.index') && ($type== $t),
    ];
}

return $navbar;
