<?php
$watermark_positions = [
    'top-left',
    'top',
    'top-right',
    'left',
    'center',
    'right',
    'bottom-left',
    'bottom',
    'bottom-right',
];

$watermark_positions = array_flip($watermark_positions);
foreach ($watermark_positions as $key => &$value) {
    $value = trans('core::image.watermark.position.'.$key);
}
return $watermark_positions;
