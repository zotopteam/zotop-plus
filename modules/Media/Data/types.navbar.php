<?php

$types  = app('modules')->data('core::file.types');
$navbar = [];

foreach ($types as $t => $n) {
    $navbar[$t] = [
        'text'   => $n,
        'href'   => route('media.index'),
        'icon'   => app('files')->icon($t),
        'active1' => app('router')->active('media.index'),
    ];
}

return $navbar;
