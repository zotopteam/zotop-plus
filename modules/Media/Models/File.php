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
    protected $fillable = ['folder_id','name','path','url','type','extension','mimetype','width','height','size','module','controller','action','field','data_id','user_id'];
}
