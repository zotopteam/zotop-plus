<?php

if ($type == 'date') {
    $min = now()->subDays(10)->format('Y-m-d');
    $max = now()->addDays(10)->format('Y-m-d');
}

if ($type == 'datetime') {
    $min = now()->subDays(10)->format('Y-m-d H:i:s');
    $max = now()->addDays(10)->format('Y-m-d H:i:s');
}

if ($type == 'time') {
    $min = now()->subMinutes(20)->format('H:i:s');
    $max = now()->addMinutes(20)->format('H:i:s');
}

if ($type == 'month') {
    $min = now()->subMonths(2)->format('Y-m-d');
    $max = now()->addMonths(2)->format('Y-m-d');
}

if ($type == 'year') {
    $min = now()->subYears(2)->format('Y-m-d');
    $max = now()->addYears(2)->format('Y-m-d');
}

return [

    [
        'type'  => $type,
        'name'  => "{$type}-range",
        'range' => '~',
    ],

    [
        'type'  => $type,
        'name'  => "{$type}-min-max",
        'min'   => $min ?? null,
        'max'   => $max ?? null,
        'theme' => '#28a745',
    ],

];
