<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','description','permissions'];
}
