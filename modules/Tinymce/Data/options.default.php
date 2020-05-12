<?php
$options = [
    'menubar'                       => false,
    'branding'                      => false,
    'contextmenu'                   => false,
    'language'                      => \App::getLocale(),
    'theme'                         => 'silver',
    'skin'                          => 'zotop',
    //'icons'                         => 'small',
    'toolbar'                       => 'undo redo | bold italic underline strikethrough forecolor backcolor removeformat | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | pagebreak charmap emoticons | table restoredraft fullscreen preview | insertfile image media template link anchor codesample',
    'plugins'                       => 'preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    'toolbar_sticky'                => true,
    'autosave_ask_before_unload'    => true,
    'autosave_interval'             => '30s',
    'autosave_retention'            => '2m',
    'quickbars_selection_toolbar'   => 'bold italic underline strikethrough forecolor removeformat | alignleft aligncenter alignright h2 h3 quicklink blockquote quickimage quicktable',
    'quickbars_insert_toolbar'      => false,
    'quickbars_image_toolbar'       => 'alignleft aligncenter alignright imageoptions',
    'noneditable_noneditable_class' => 'mceNonEditable',
    'width'                         => '100%',
    'min_height'                    => 200,
    'max_height'                    => 1200,
    'height'                        => 600,
    'resize'                        => true,
    'image_caption'                 => true,
    'image_description'             => true,
    'image_advtab'                  => true,
    'images_upload_credentials'     => true,
    'paste_data_images'             => true,    
    'elementpath'                   => false,
    'draggable_modal'               => true,


    //'tools'                     => 'default',

    // 'convert_urls '             => false,
    // 'invalid_elements'          => 'script,applet',
    // 'force_br_newlines'         => false,
    // 'force_p_newlines'          => true,
    // 'forced_root_block'         => 'p',
    // 'relative_urls'             => false,
    'content_css'               => [],
];

// 加载主题的内容样式
$theme = config('site.theme', 'default');

if (app('files')->exists(app('themes')->path($theme.':assets/css/content.css'))) {
    $options['content_css'][] = app('themes')->asset($theme.':css/content.css');
}

return $options;
