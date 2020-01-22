<?php

namespace Modules\Developer\Hooks;

use App\Modules\Facades\Module;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class ModuleMake
{

    /**
     * 模块风格
     * @param  array $start
     * @return array
     */
    public function full($module)
    {
        Artisan::call('module:make-controller', [
            'module' => $module,
            'name'   => 'index',
            '--type' => 'frontend',
        ]);

        Artisan::call('module:make-controller', [
            'module' => $module,
            'name'   => 'index',
            '--type' => 'backend',
        ]);

        Artisan::call('module:make-controller', [
            'module' => $module,
            'name'   => 'index',
            '--type' => 'api',
        ]);        
    }


}
