<?php

return [
    'title'                    => '开发助手',
    'description'              => '简化开发步骤，协助创建各种数据和文件',
    
    'develop'                  => '模块开发助手',
    'develop.description'      => '简化模块开发，快速创建模块和相应文件',
    'create'                   => '新建模块',
    'edit'                     => '开发模块',
    'show'                     => '模块信息',
    'path'                     => '模块路径',
    
    'file.name'                => '文件名',
    'file.path'                => '文件路径',
    'file.lastmodified'        => '最后修改时间',
    
    
    
    'name.label'               => '名称',
    'name.help'                => '模块的唯一标示符，勿与其它模块重复，只允许英文',
    
    'plain.label'              => '类型',
    'plain.help'               => '',
    'plain.true'               => '简单模块，不创建控制器和视图等文件',
    'plain.false'              => '完整模块，包含控制器和视图等文件',
    
    'alias.label'              => '别名',
    
    'title.label'              => '标题',
    'title.help'               => '默认值 :0::module.title 为使用语言翻译，也可以直接显示文字，建议使用语言翻译',
    
    'description.label'        => '描述',
    'description.help'         => '默认值 :0::module.description 为使用语言翻译，也可以直接显示文字，建议使用语言翻译',
    
    'version.label'            => '版本',
    'order.label'              => '排序',    
    'author.label'             => '开发者',
    'email.label'              => '电子邮件',
    'homepage.label'           => '主页',
    
    'providers.label'          => '服务提供者',
    'providers.help'           => '请直接修改 :0\module.json 中的 providers',
    
    'aliases.label'            => '门面',    
    'aliases.help'             => '请直接修改 :0\module.json 中的 aliases',
    
    'files.label'              => '全局加载文件',    
    'files.help'               => '请直接修改 :0\module.json 中的 files',
    
    'controller'               => '控制器',
    
    'controller.admin'         => '后台控制器',
    'controller.front'         => '前台控制器',
    
    'controller_name.label'    => '控制器名称',
    'controller_name.help'     => '如：test，将创建TestController',
    
    'controller_type.label'    => '控制器类型',
    'controller_type.help'     => '不同控制器类型默认创建的控制器和模板不同',
    'controller_type.simple'   => '基本控制器，只含Index动作和对应模板',
    'controller_type.resource' => '资源控制器，包含Index/Create/Update/Destory 等动作和对应模板',    

];
