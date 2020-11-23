<?php

namespace App\Support\Eloquent;

use App\Support\Eloquent\Traits\HasQueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use HasFactory, HasQueryFilter;
}

