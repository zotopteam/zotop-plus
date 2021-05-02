<?php

$return = [];
$themes = app('themes')->channel('front');

foreach ($themes as $theme) {

    $value = $theme->name;
    $image = preview($theme->path . '/theme.jpg', 200, 150, 'fit');
    $title = $theme->title;
    $description = $theme->description;

    $return[$value] = [
        'image' => $image,
        'title' => $title,
    ];

}

return $return;
