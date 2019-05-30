<?php
use \Modules\Core\Models\Role;

if (isset($user)) {
    return $user->roles->pluck('id')->toArray();
}

return Role::all()->pluck('name', 'id')->toArray();
