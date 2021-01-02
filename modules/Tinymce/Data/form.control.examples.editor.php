<?php

return [
    [
        'type' => $type,
        'name' => $type,
    ],

    [
        'type'        => $type,
        'name'        => "{$type}_height",
        'height'      => 300,
        'placeholder' => 'placeholder……',
        'options'     => 'mini',
    ],


    [
        'type'    => $type,
        'name'    => "{$type}_toobar",
        'height'  => 300,
        'toolbar' => 'bold forecolor',
        'resize'  => 'false',
    ],

    [
        'type'    => $type,
        'name'    => "{$type}_menubar",
        'height'  => 300,
        'options' => [
            'menubar' => true,
            'toolbar' => 'undo redo copy paste pastetext searchreplace removeformat',
        ],
    ],
];
