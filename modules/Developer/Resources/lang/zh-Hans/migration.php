<?php

return [
    'title'         => '数据迁移',
    'index'         => '迁移列表',
    'create'        => '创建迁移',
    'execute'       => '执行命令',
    'migrate'       => '执行迁移',
    'migrate.tips'  => '运行当前模块所有未运行过的迁移',
    'rollback'      => '回滚一步',
    'rollback.tips' => '回滚当前模块最后一次迁移，注意：操作会导致模块数据丢失',
    'reset'         => '回滚全部',
    'reset.tips'    => '回滚当前模块所有迁移，注意：操作会导致模块数据丢失',
    'refresh'       => '回滚迁移',
    'refresh.tips'  => '回滚当前模块所有迁移，并重新迁移全部，注意：操作会导致模块数据丢失',
    'path'          => '文件位置',
    'artisan'       => 'Artisan命令', 
    'name'          => '迁移文件名称',
    'name.help'     => '如：create_test_table，创建成功后，可以编写该迁移文件，然后执行迁移',
];
