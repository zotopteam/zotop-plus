<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'media_files';    
    protected $fillable = [];
}
