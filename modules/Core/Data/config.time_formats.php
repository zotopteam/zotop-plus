<?php
// 本地日期格式
$locale_time_formats = [];
if (app('translator')->hasForLocale('core::config.locale.time_formats')) {
    $locale_time_formats = app('translator')->trans('core::config.locale.time_formats');
    $locale_time_formats = explode('||', $locale_time_formats);
    $locale_time_formats = array_flip($locale_time_formats);
    foreach ($locale_time_formats as $key=>&$value) {
        $value = now()->format($key);
    }
}

// 时间格式选项
return $locale_time_formats + [
    'H:i:s' => now()->format('H:i:s'),
    'H:i'   => now()->format('H:i'),
];
