<?php
    
return [
    'title'          => '数据迁移',
    'index'          => '迁移列表',
    'create'         => '创建迁移',
    'execute'        => '执行命令',
    'migrate'        => '迁移全部',
    'migrated'       => '已迁移',
    'migrate.tips'   => '运行当前模块所有未运行过的迁移',
    'rollback'       => '回滚一步',
    'rollback.tips'  => '回滚当前模块最后一次迁移，注意：操作会导致模块数据丢失',
    'reset'          => '回滚全部',
    'reset.tips'     => '回滚当前模块所有迁移，注意：操作会导致模块数据丢失',
    'refresh'        => '回滚迁移',
    'refresh.tips'   => '回滚当前模块所有迁移，并重新迁移全部，注意：操作会导致模块数据丢失',
    'name'           => '迁移名称或者表名称',
    'name.help'      => '只支持英文（小写）和数字，必须以英文开头，表名称不含前缀，创建成功后，可以编写该迁移文件，然后执行迁移',
    
    'artisan'        => '迁移类型',
    'artisan.help'   => '',    
    'artisan.blank'  => '空白迁移',
    'artisan.create' => '创建表迁移',
    'artisan.update' => '更新表迁移',
    'artisan.drop'   => '删除表迁移',
    
    'file.migrate'   => '迁移',
    'file.reset'     => '回滚',
    'file.refresh'   => '回滚迁移',
];
