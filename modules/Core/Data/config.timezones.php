<?php
// 时区选项
$timezones = [];
foreach(timezone_identifiers_list() as $key => $zone) {
    $continents = explode('/',$zone)[0];
    $timezones[$continents][$zone] = 'UTC/GMT '.(new \DateTime(null, new \DateTimeZone($zone)))->format('P').' - '.$zone;    
}

return $timezones;
