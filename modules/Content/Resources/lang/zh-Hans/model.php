<?php

return [
    'title'               => '模型',
    'description'         => '内容模型管理',

    'create'              => '新建模型',
    'edit'                => '设置',
    'export'              => '导出',
    'import'              => '导入',

    'delete.notempty'     => '该模型数据已经有内容数据，禁止删除',
    //'dirtyid.forbidden' => '已经有自定义字段或者已经有该模型数据，无法修改模型标识',

    'name.label'          => '模型名称',
    'name.help'           => '可读名称，如： 页面、文章、产品、下载等',

    'id.label'            => '模型标识',
    'id.help'             => '模型唯一标识，如： page、article，只允许英文字母（小写）、数字和下划线 ( _ )，最大长度64位',
    'id.regex'            => '模型标识规则：英文字母（小写）开头，英文字母（小写）或者数字结尾，中间可以有下划线 ( _ )',

    'description.label'   => '模型说明',
    'description.help'    => '模型说明，最大255个字符',   

    'template.label'      => '内容页面模板',
    'template.help'       => '内容的详细页面模板，如果没有内容页（如“链接”模型）则无需填写此项',        
];
