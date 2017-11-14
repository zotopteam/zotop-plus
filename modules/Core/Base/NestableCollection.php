<?php
namespace Modules\Core\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class NestableCollection extends Collection
{
    
    public function parents()
    {
        dd('parents');
    }
}
