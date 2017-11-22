<?php
// 本地日期格式
$locale_date_formats = [];
if (app('translator')->hasForLocale('core::config.locale.date_formats')) {
    $locale_date_formats = app('translator')->trans('core::config.locale.date_formats');
    $locale_date_formats = explode('||', $locale_date_formats);
    $locale_date_formats = array_flip($locale_date_formats);
    foreach ($locale_date_formats as $key=>&$value) {
        $value = now()->format($key);
    }
}
// 日期格式选项
return $locale_date_formats + [
    'Y-m-d' => now()->format('Y-m-d'),
    'Y/m/d' => now()->format('Y/m/d'),
    'Y.m.d' => now()->format('Y.m.d'),
];
