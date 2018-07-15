<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $table = 'block';
    protected $fillable = ["code","name"];
}
