<?php

return [
    'title'                  => '数据表',
    'description'            => '模块数据表管理，数据表前缀需要为模块名',
    'index'                  => '数据表列表',
    'create'                 => '创建数据表',

    'name'                   => '数据表名称',
    'name.help'              => '如：test，将创建 :0',

    'columns'                => '数据表字段',
    'columns.help'           => '',

    'migration'              => '生成迁移',

    'fields.name'            => '名字',
    'fields.type'            => '类型',
    'fields.length'          => '长度/值',
    'fields.nullable'        => '空',
    'fields.index'           => '索引',
    'fields.unsigned'        => '无符号',
    'fields.auto_increment'  => '自增',
    'fields.default'         => '默认值',
    'fields.comment'         => '注释',

    'fields.add'             => '添加新字段',
    'fields.add_timestamps'  => '添加时间戳',
    'fields.add_softdeletes' => '添加软删除',
];
