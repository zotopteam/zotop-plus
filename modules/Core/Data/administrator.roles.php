<?php
use \Modules\Core\Models\Role;

if ($args && $user = $args[0]) {
    return $user->roles->pluck('id')->toArray();
}

return Role::all()->pluck('name', 'id')->toArray();
