<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Installer Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines for Installer
    |
    */
    
    'title'                          => ':0 安装程序',
    
    'wizard.welcome'                 => '欢迎',
    'wizard.check'                   => '检测',
    'wizard.config'                  => '设置',
    'wizard.database'                => '数据库',
    'wizard.modules'                 => '模块',    
    'wizard.finished'                => '完成',
    
    'prev'                           => '上一步',
    'next'                           => '下一步',
    'retry'                          => '重试',
    
    'welcome'                        => '欢迎使用 :0',
    'welcome.description'            => '基于Laravel5和bootstrap4，模块化开发，打造更简洁、更易用的内容管理系统',
    
    'check.success'                  => '恭喜！检测通过',
    'check.success.description'      => '您的服务器环境满足安装要求',
    
    'check.error'                    => '未检测通过',
    'check.error.description'        => '您的服务器环境不能满足安装要求，请根据下面列表修改后重试',
    
    'check.key'                      => '检测项目',
    'check.need'                     => '安装需求',
    'check.current'                  => '当前配置',
    
    'check.php_version'              => 'PHP版本',
    'check.php_extensions'           => 'PHP扩展：:0',
    'check.apache'                   => 'Apache：:0',
    'check.permission'               => '文件目录：:0',
    'check.enabled'                  => '开启',
    'check.disabled'                 => '关闭',
    'check.notfound'                 => '不存在',
    
    'config'                         => '搭建网站数据存储平台',
    'config.description'             => '请设置网站管理员信息和数据库连接参数等必填信息',
    
    'config.site'                    => '站点设置',
    'config.site.name'               => '站点名称',
    'config.site.name.value'         => '逐涛网',
    
    'config.admin.username'          => '创始人帐号',
    'config.admin.password'          => '创始人密码',
    'config.admin.email'             => '创始人邮箱',
    
    'config.db'                      => '数据库设置',
    
    'config.db.connection'           => '连接类型',
    'config.db.host'                 => '主机地址',
    'config.db.port'                 => '端口', 
    'config.db.database'             => '数据库', 
    'config.db.username'             => '帐号',
    'config.db.password'             => '密码',
    'config.db.prefix'               => '数据表前缀',
    
    'modules'                        => '安装模块',
    'modules.description'            => '正在开始安装，请稍后……',
    
    'modules.installing'             => '模块正在安装中，请稍后……', 
    'modules.installed'              => '模块安装成功，请稍后……', 
    'modules.completed'              => '正在完成安装，请稍后……', 
    
    'database'                       => '数据库连接正常',
    'database.description'           => '恭喜，数据库可以正常使用', 
    'database.installed'             => '你可能已经安装过了', 
    'database.hastables'             => ':0/:1 已有数据表',
    'database.installed.description' => '数据库 [:0/:1] 已有数据表，如需继续安装，请先 手动清空数据库 或者 覆盖安装',     
    'database.override'              => '覆盖安装', 
    'database.override.confirm'      => '警告：该操作将清空全部数据表，请先备份数据',
    
    'finished'                       => '恭喜！安装成功',
    'finished.description'           => '你现在可以开始享受 :0 带来的全新体验 ',
    
    'site.index'                     => '网站首页',
    'admin.index'                    => '管理后台',
];
