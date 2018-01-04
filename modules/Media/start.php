<?php
/**
 * 扩展后台全局导航
 */
\Filter::listen('global.start','Modules\Media\Hook\Listener@start', 80);

/**
 * 扩展开始菜单
 */
\Filter::listen('global.navbar', 'Modules\Media\Hook\Listener@navbar');

/**
 * 监听系统图片上传
 */
\Filter::listen('core.file.upload', 'Modules\Media\Hook\Listener@upload');

/**
 * 监听上传工具字段，从媒体中选择
 */
\Filter::listen('core::field.upload.tools', 'Modules\Media\Hook\Listener@select');

\Filter::listen('media::select.navbar', 'Modules\Media\Hook\Listener@select');
