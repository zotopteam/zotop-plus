<?php

use Zotop\Support\Facades\Filter;

/*
 * 编辑器模式
 */
Filter::listen('tinymce.editor.options', 'Modules\Tinymce\Hooks\Listener@options');
Filter::listen('tinymce.editor.options', 'Modules\Tinymce\Hooks\Listener@tools');
