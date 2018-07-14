<?php

namespace Modules\Section\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'section';
    protected $fillable = ["code","name"];
}
