<?php
// 上传参数
$upload_params = [
    '_token'     => csrf_token(),
    'module'     => app('current.module'),
    'controller' => app('current.controller'),
    'action'     => app('current.action'),
    'field'      => $name ?? '',
    'source_id'  => $source_id ?? '',
];

// tinymce 参数
$options = [
    'menubar'                       => false,
    'branding'                      => false,
    'contextmenu'                   => false,
    'language'                      => \App::getLocale(),
    'theme'                         => 'silver',
    'skin'                          => 'zotop',
    'icons'                         => 'zotop',
    'toolbar'                       => 'undo redo | bold italic underline strikethrough forecolor backcolor removeformat | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | tools table quickimage media codesample charmap link emoticons | pagebreak template anchor toc | restoredraft preview fullscreen',
    'plugins'                       => 'preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    'toolbar_sticky'                => false,
    'autosave_ask_before_unload'    => false,
    'autosave_interval'             => '30s',
    'autosave_retention'            => '2m',
    'quickbars_selection_toolbar'   => 'bold italic underline strikethrough forecolor removeformat | alignleft aligncenter alignright h2 h3 quicklink blockquote',
    'quickbars_insert_toolbar'      => false,
    'quickbars_image_toolbar'       => 'alignleft aligncenter alignright imageoptions',
    'noneditable_noneditable_class' => 'mceNonEditable',
    'width'                         => '100%',
    'min_height'                    => 200,
    'max_height'                    => 1200,
    'height'                        => 600,
    'resize'                        => true,
    'image_caption'                 => true,
    'image_title'                   => true,
    'image_description'             => true,
    'image_advtab'                  => true,
    'images_upload_url'             => route('core.file.upload', $upload_params),
    'images_upload_credentials'     => true,
    'images_reuse_filename'         => true,
    'imagetools_toolbar'            => 'alignleft aligncenter alignright | editimage imageoptions',
    'paste_data_images'             => true,    
    'elementpath'                   => false,
    'draggable_modal'               => true,
    'convert_urls '                 => false,
    'invalid_elements'              => 'script,applet',
    'force_br_newlines'             => false,
    'force_p_newlines'              => true,
    'forced_root_block'             => 'p',
    'relative_urls'                 => false,
    'content_css'                   => [],
    //'placeholder'                 => trans('tinymce::tinymce.placeholder'),
];

// 加载主题的内容样式
$theme = app('themes')->find(config('site.theme', 'default'));

if ($content_css = optional($theme)->asset('css/content.css')) {
     $options['content_css'][] = $content_css;
}

return $options;
