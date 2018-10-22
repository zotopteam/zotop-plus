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
    'indexes'                     => '数据表索引',

    'migration.create'            => '生成创建迁移',
    'migration.override'          => '覆盖创建迁移',
    'migration.update'            => '生成更新迁移',

    'column.name'                 => '名字',
    'column.type'                 => '类型',
    'column.length'               => '长度/值',
    'column.nullable'             => '空',
    'column.index'                => '索引',
    'column.unsigned'             => '无符号',
    'column.increments'           => '自增',
    'column.default'              => '默认值',
    'column.comment'              => '注释',

    'column.add'                  => '新字段',
    'column.add_timestamps'       => '时间戳',
    'column.add_softdeletes'      => '软删除',

    'column.exists'                => '字段已经存在',

    'column.validator.columnname' => '长度2-20，允许小写英文字母、数字和下划线，并且仅能字母开头，不以下划线结尾',
    'column.validator.uniquename' => '标识已经存在，请使用其它标识',

    'index.name'                  => '名称',
    'index.type'                  => '类型',
    'index.columns'               => '字段',

    'index.primary'               => '主键',
    'index.index'                 => '索引',
    'index.unique'                => '唯一',
    'index.unselect'              => '请选择要索引的字段',
    'index.exists'                => '索引已经存在',
];
