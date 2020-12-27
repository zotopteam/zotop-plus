<?php
return [
    'title'        => '表单助手',
    'description'  => '表单设计和表单控件',
    'example'      => '示例',
    'code'         => '代码',
    'group.common' => '常规控件',
    'group.dates'  => '日期时间',

    'attribute.value.help' => '普通的值可以通过简单的 HTML 属性来传递给组件。PHP 表达式和变量应该通过以 : 字符作为前缀的变量来进行传递，如：:option="[1,2]',

    'control.hidden'          => '隐藏 hidden',
    'control.text'            => '单行文本 text',
    'control.number'          => '数字 number',
    'control.password'        => '密码 password',
    'control.email'           => '邮件 email',
    'control.url'             => '链接 url',
    'control.tel'             => '电话 tel',
    'control.date'            => '日期 date',
    'control.datetime'        => '日期时间 datetime',
    'control.time'            => '时间 time',
    'control.month'           => '月 month',
    'control.week'            => '周 week',
    'control.range'           => '范围 range',
    'control.file'            => '文件 file',
    'control.color'           => '颜色 color',
    'control.search'          => '搜索 search',
    'control.textarea'        => '多行文本 textarea',
    'control.button'          => '按钮 button',
    'control.submit'          => '提交按钮 submit',
    'control.save'            => '保存按钮 save',
    'control.reset'           => '重置按钮 reset',
    'control.select'          => '下拉选择 select',
    'control.radio'           => '单选框 radio',
    'control.checkbox'        => '复选框 checkbox',
    'control.view'            => '视图选择 view',
    'control.translate'       => '翻译 translate',
    'control.slug'            => 'Slug转换 slug',
    'control.radio-cards'     => '单选卡片 radio-cards',
    'control.radios'          => '单选组 radios',
    'control.bool'            => '是/否 bool',
    'control.enable'          => '启用/禁用 enable',
    'control.checkboxes'      => '多选组 checkboxes',
    'control.year'            => '年 year',
    'control.toggle'          => '切换 toggle',
    'control.editor'          => '富文本编辑器 editor',
    'control.code'            => '代码编辑器 code',
    'control.markdown'        => 'markdown',
    'control.icon'            => '图标选择 icon',
    'control.upload-image'    => '上传图片 upload-image',
    'control.upload-document' => '上传文档 upload-document',
    'control.upload-archive'  => '上传压缩包 upload-archive',
    'control.upload-video'    => '上传视频 upload-video',
    'control.upload-audio'    => '上传音频 upload-audio',
    'control.upload'          => '上传 upload',
    'control.gallery'         => '图集 gallery',

    'control.attributes'          => '控件标签',
    'control.attributes.key'      => '属性',
    'control.attributes.type'     => '值类型',
    'control.attributes.required' => '必选',
    'control.attributes.example'  => '值/示例',
    'control.attributes.text'     => '描述',

    'control.attribute.type'         => '控件类型',
    'control.attribute.id'           => '规定控件的唯一 id，可选，如果不填则从name转换',
    'control.attribute.name'         => '定义控件的名称，后端可通过该名称接收值',
    'control.attribute.value'        => '规定控件的值，可选，如果没有设定该标签将从form的bind属性自动获取',
    'control.attribute.class'        => '规定控件的一个或多个类名（引用样式表中的类），在控件默认类名后增加',
    'control.attribute.style'        => '规定控件的行内 CSS 样式',
    'control.attribute.tabindex'     => '规定控件的 tab 键次序',
    'control.attribute.placeholder'  => '规定帮助用户填写输入字段的提示',
    'control.attribute.pattern'      => '规定输入字段的值的模式或格式',
    'control.attribute.data-*'       => '用于存储页面或应用程序的私有定制数据',
    'control.attribute.autocomplete' => '规定是否使用输入字段的自动完成功能',

    'select.attributes.options'  => '选项，支持一维数组和二维数组，二维数组的key为optgroup标签',
    'select.attributes.multiple' => '规定可选择多个选项',

    'number.attributes.step' => '选项，支持一维数组和二维数组，二维数组的key为optgroup标签',

    'textarea.attributes.cols' => '规定文本区内的可见宽度',
    'textarea.attributes.rows' => '规定文本区内的可见行数',

    'date.attributes.range' => '规定范围选择的分隔符',
    'date.attributes.min'   => '规定选择范围的最小值',
    'date.attributes.max'   => '规定选择范围的最大值',
    'date.attributes.theme' => '设置控件的主题颜色',

];