<?php

return [
    'title'                  => '管理员',
    'description'            => '系统管理员的管理、添加等操作',

    'model.super'            => '系统管理员',
    'model.admin'            => '管理员',
    
    'index'                  => '管理员列表',
    'create'                 => '添加管理员',
    'edit'                   => '编辑管理员',
    'status'                 => '启用/禁用',
    'destroy'                => '删除管理员',
    
    'form.base'              => '账户密码',
    'form.profile'           => '用户资料',
    
    'login_at.label'         => '最后登录',
    
    'username.label'         => '用户名',
    'username.help'          => '用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符',
    
    'email.label'            => '电子邮件',
    'email.required'         => '请输入您的电子邮件',    
    'email.placeholder'      => 'example@example.com',
    'email.help'             => '请输入电子邮件地址',
    
    'mobile.label'           => '手机号',
    'mobile.help'            => '请输入手机号',
    
    'nickname.label'         => '昵称',
    'sign.label'             => '签名',
    
    
    'password.label'         => '密码',
    'password.help'          => '请输入您要设置的密码，6-32位',
    'password_confirm.label' => '确认密码',
    'password_confirm.help'  => '为确保安全，请再次输入密码',
    
    'password_new.label'     => '新密码',
    'password_new.help'      => '如需修改请输入新密码，不修改请留空',

    'roles.label'            => '角色',
];
