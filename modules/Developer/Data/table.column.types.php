<?php
$types = [];

$numbers = [    
    'tinyint',
    'smallint',
    'mediumint',
    'bigint',
    'integer',

    'decimal',
    'float',
    'double',

    //'bit',
    'boolean',
    //'serial'
];

$strings = [
    'char',
    'string',

    //'tinytext',
    'text',
    'mediumtext',
    'longtext',

    'binary',
    // 'varbinary',

    // 'tinyblob',
    // 'blob',
    // 'mediumblob',
    // 'longblob',      

    'enum',
    'json',
    //'set', 暂不支持 set 类型
];

$datetime = [
    'date',
    'datetime',
    'timestamp',
    
    'time',
    'year',
];

$types = [
    'numbers'  => array_combine($numbers, array_map('strtoupper', $numbers)),
    'strings'  => array_combine($strings, array_map('strtoupper', $strings)),
    'datetime' => array_combine($datetime, array_map('strtoupper', $datetime)),
];

return $types;
