<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Module;

class Block extends Model
{
    protected $table = 'block';
    protected $fillable = ["code","name"];

    public static function type($type=null, $field=null, $default=null)
    {
        $types = Module::data('block::types');

        if (empty($type)) {
            return $types;
        }

        if (empty($field)) {
            return $types[$type] ?? [];
        }

        return $types[$type][$field] ?? $default;
    }
}
