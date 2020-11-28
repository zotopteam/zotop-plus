<?php
return [
    'title'       => '系统设置',
    'description' => '上传、水印、邮件、语言、区域、安全等设置',

    'upload' => '文件上传',
    'image'  => '图片缩放和水印',
    'font'   => '字体',
    'cache'  => '缓存',
    'mail'   => '邮件发送',
    'locale' => '区域和语言',
    'safe'   => '系统安全',

    'upload.base'             => '上传设置',
    'upload.types.label'      => '上传类型',
    'upload.types.type'       => '类型',
    'upload.types.extensions' => '允许文件格式',
    'upload.types.maxsize'    => '最大值',
    'upload.types.enabled'    => '启用',
    'upload.types.help'       => '',

    'upload.dir.label' => '上传目录',
    'upload.dir.help'  => '',
    'upload.dir.ym'    => '以 年/月 目录形式组织上传内容 如：:0',
    'upload.dir.ymd'   => '以 年/月/日 目录形式组织上传内容 如：:0',

    'mail.base'               => '基本设置',
    'mail.driver.label'       => '发送方式',
    'mail.driver.help'        => '',
    'mail.drivers.smtp'       => '使用 SMTP 服务器发送',
    'mail.drivers.mail'       => '使用 mail 函数发送',
    'mail.drivers.sendmail'   => '使用 sendmail 发送',
    'mail.drivers.log'        => '日志模式',
    'mail.drivers.log.help'   => '日志模式并不会真实发送邮件，仅供开发阶段测试，如需真实发送邮件，请选择其他发送方式',
    'mail.from.address.label' => '发送人邮箱',
    'mail.from.address.help'  => '',
    'mail.from.name.label'    => '发送人名称',
    'mail.from.name.help'     => '',
    'mail.smtp'               => 'SMTP设置',
    'mail.host.label'         => 'SMTP主机地址',
    'mail.host.help'          => '',
    'mail.port.label'         => 'SMTP主机端口',
    'mail.port.help'          => '',
    'mail.encryption.label'   => '传输协议加密方式',
    'mail.encryption.help'    => '',
    'mail.username.label'     => 'SMTP用户名',
    'mail.username.help'      => '',
    'mail.password.label'     => 'SMTP密码',
    'mail.password.help'      => '',
    'mail.test'               => '发送测试',
    'mail.test.label'         => '验证邮箱',
    'mail.test.help'          => '尝试向验证邮箱发送一个验证码，如果邮件发送设置正确，将会收到含有验证码的邮件',
    'mail.test.send'          => '发送',

    'locale.timezone' => '语言和时区',

    'locale.label'      => '系统语言',
    'locale.help'       => '',
    'languages.zh-hant' => '中文繁体',
    'languages.zh-hans' => '中文简体',
    'languages.en'      => 'English',
    'timezone.label'    => '系统时区',
    'timezone.help'     => '选择与您在同一时区的城市，协调世界时（UTC）为：:utc, 本地时间为：:locale',

    'locale.datetime'     => '日期和时间',
    'locale.date_formats' => 'Y年m月d日||y年n月j日', //将作为本地日期选项，多个用||分割
    'locale.time_formats' => 'H点i分', //将作为本地时间选项，多个用||分割
    'date_format.label'   => '日期格式',
    'date_format.help'    => '',
    'time_format.label'   => '时间格式',
    'time_format.help'    => '',

    'safe.base'                   => '运行模式',
    'env.label'                   => '应用环境',
    'env.help'                    => '',
    'envs.production'             => '生产环境',
    'envs.production.description' => 'production，用于线上部署，开启配置和路由缓存，加快运行速度',
    'envs.local'                  => '本地环境',
    'envs.local.description'      => 'local，用于本地开发',
    'envs.testing'                => '测试环境',
    'envs.testing.description'    => 'testing，用于程序测试',

    'key.label'   => '安全码',
    'key.help'    => '更改后将退出系统并需要重新登录',
    'debug.label' => '调试模式',
    'debug.help'  => '',

    'safe.admin'         => '后台安全',
    'admin_prefix.label' => '后台前缀',
    'admin_prefix.help'  => '更改后将改变系统后台的URL地址前缀，默认为：admin',

    'log.label'     => '操作日志',
    'log.expire'    => '有效期',
    'log.unit'      => '天',
    'log.help'      => '开启后将自动记录用户的操作，超出有效期的日志将被自动删除',
    'log_sql.label' => '记录SQL',
    'log_sql.help'  => '开启后将在日志中自动记录所有执行的sql',
];
