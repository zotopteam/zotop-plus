<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Nestable;

class Folder extends Model
{
    use Nestable;
    
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'media_folders';    
    protected $fillable = ['parent_id','name','settings','sort','disabled','created_at','updated_at'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];    
}
