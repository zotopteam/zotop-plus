<?php
return [
    'title'        => 'Permission assistant',
    'description'  => 'Assist in creating and verifying module permission profiles',
    'key'          => 'Node key name',
    'val'          => 'Node name',
    'name'         => 'Display name',
    'scan'         => 'Scan and generate',
    'scan.confirm' => 'Warning: scan and build will overwrite the existing permission configuration',
    'scan.success' => 'Scan and generate operation succeeded, original permission configuration backup succeeded',
    'scan.empty'   => 'The permission node is not scanned in the current module route. Please add [allow::0.test] Middleware in the route configuration first',
    'missing'      => 'There are the following nodes in the route, but not in the permission profile. Please add them in the permission profile',
    'question'     => 'This node exists in the permission configuration, but not in the route. If it is no longer used, please remove it from the permission configuration file',
];
