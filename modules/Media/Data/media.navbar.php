<?php
use File;
use Module;

$types  = Module::data('core::file.types');
$navbar = [];

foreach ($types as $t => $n) {
    $navbar[$t] = [
        'text'   => $n,
        'href'   => route('media.index',[$folder_id, $t]),
        'icon'   => File::icon($t),
        'active' => Route::active('media.index') && ($type== $t),
    ];
}

return $navbar;
