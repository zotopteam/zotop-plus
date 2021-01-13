<?php

use Modules\Core\Enums\RoleTypeEnum;
use Modules\Core\Models\Role;

if (isset($user)) {
    return $user->roles->pluck('id')->toArray();
}

return Role::where('type', RoleTypeEnum::ADMIN)->pluck('name', 'id')->toArray();
