<?php

return [

    // 简单选择
    [
        'type'    => $type,
        'name'    => 'test1',
        'options' => [
            'red',
            'blue',
            'black',
        ],
    ],

    // 简单选择
    [
        'type'     => $type,
        'name'     => 'test1',
        'options'  => [
            'red',
            'blue',
            'black',
        ],
        'multiple' => true,
    ],

    // 选择组
    [
        'type'        => $type,
        'name'        => 'test2',
        'options'     => [
            'China'   => [
                '1' => 'Bei Jing',
                '2' => 'Shang Hai',
            ],
            'America' => [
                '3' => 'Washington DC',
                '4' => 'New York',
            ],
        ],
        'placeholder' => 'Please select city……',
    ],
];
