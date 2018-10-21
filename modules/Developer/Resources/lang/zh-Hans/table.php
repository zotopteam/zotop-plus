<?php

return [
    'title'                       => '数据表',
    'description'                 => '模块数据表管理，数据表前缀需要为模块名',
    'index'                       => '数据表列表',
    'create'                      => '创建',
    'edit'                        => '修改',
    'structure'                   => '结构',
    'rename'                      => '重命名',
    'drop'                        => '删除',

    'exists'                      => '数据表 :0 已经存在',

    'name'                        => '数据表名称',
    'name.help'                   => '如：test，不含数据表前缀',
    'name.error'                  => '数据表名称必须等于 :0 或者以 :0_ 开头，不能包含特殊字符或者以下划线结尾',

    'columns'                     => '数据表字段',
    'columns.help'                => '',

    'migration'                   => '生成迁移',

    'column.name'                 => '名字',
    'column.type'                 => '类型',
    'column.length'               => '长度/值',
    'column.nullable'             => '空',
    'column.index'                => '索引',
    'column.unsigned'             => '无符号',
    'column.increments'           => '自增',
    'column.default'              => '默认值',
    'column.comment'              => '注释',

    'column.add'                  => '添加新字段',
    'column.add_timestamps'       => '添加时间戳',
    'column.add_softdeletes'      => '添加软删除',

    'column.validator.columnname'  => '长度2-20，允许小写英文字母、数字和下划线，并且仅能字母开头，不以下划线结尾',
    'column.validator.uniquename' => '标识已经存在，请使用其它标识',
];
