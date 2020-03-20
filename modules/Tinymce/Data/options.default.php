<?php
$options = [
    'menubar'                   => false,
    'toolbar'                   => 'undo redo copy paste pastetext searchreplace removeformat onekeyclear | formatselect fontselect fontsizeselect | forecolor backcolor | bold italic underline strikethrough blockquote | subscript superscript |  alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link unlink anchor | image files audio video media | insertdatetime table  charmap emoticons  hr | visualchars nonbreaking codesample template pagebreak | localautosave preview code fullscreen',
    'plugins'                   => 'advlist autolink lists link image media charmap preview anchor searchreplace code fullscreen table hr textcolor colorpicker textpattern imagetools tabfocus codesample wordcount nonbreaking noneditable placeholder localautosave onekeyclear',
    'tools'                     => 'default',
    'width'                     => '100%',
    'height'                    => '400',
    'toolbar_items_size'        => 'small',
    'inline'                    => false,
    'language'                  => \App::getLocale(),
    'theme'                     => 'modern',
    'skin'                      => 'zotop',
    'resize'                    => true,
    'image_caption'             => true,
    'imagetools_toolbar'        => 'alignleft aligncenter alignright imageoptions',
    'image_advtab'              => true,
    'images_upload_credentials' => true,
    'paste_data_images'         => true,
    'convert_urls '             => false,
    'invalid_elements'          => 'script,applet',
    'force_br_newlines'         => false,
    'force_p_newlines'          => true,
    'forced_root_block'         => 'p',
    'relative_urls'             => false,
    'content_css'               => [],
];

// 加载主题的内容样式
$theme = config('site.theme', 'default');

if (app('files')->exists(app('themes')->path($theme.':assets/css/content.css'))) {
    $options['content_css'][] = app('themes')->asset($theme.':css/content.css');
}

return $options;
