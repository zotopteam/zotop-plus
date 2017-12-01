<?php

return [
    'title'             => '角色',
    'description'       => '角色定义一组拥有若干权限的用户，通过修改角色权限来设定用户权限和功能',    
    'required'          => '尚无任何角色，请先添加角色',
    
    'index'             => '角色列表',
    'create'            => '添加角色',
    'edit'              => '编辑角色',
    'status'            => '启用/禁用',
    'destroy'           => '删除角色',

    'form.base'         => '基本信息',
    'form.permission'   => '权限设置',

    'status.label'      => '状态',
    'status.help'       => '',

    'name.label'        => '名称',
    'name.help'         => '此角色的名称。如“管理员”、“编辑”、“开发人员”等',

    'code.label'        => '唯一编码',
    'code.help'         => ' 一个唯一的机器可读名字。仅能包含小写字母、数字和下划线',    
    
    'description.label' => '描述',
    'description.help'  => '',

    'select.all'        => '全部允许',
    'select.none'       => '全部拒绝',   
];
