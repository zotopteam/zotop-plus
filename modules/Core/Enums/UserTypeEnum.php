<?php

namespace Modules\Core\Enums;

use App\Support\Enum;

class UserTypeEnum extends Enum
{
    /**
     * 超级管理员
     */
    const SUPER = 'super';

    /**
     * 管理员
     */
    const ADMIN = 'admin';
}
