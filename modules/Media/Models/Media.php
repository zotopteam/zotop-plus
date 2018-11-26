<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\UserRelation;
use Format;


class Media extends Model
{
	use UserRelation;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'media';
	
	
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['parent_id','type','name','path','url','extension','mimetype','width','height','size','module','controller','action','field','data_id','user_id','sort'];
	
	
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];
	
	
    /**
     * 属性转换
     *
     * @var array
     */
    //protected $casts = [];
	
	
    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    //public $timestamps = false;

    /**
     * 获取友好的创建日期。
     *
     * @return string
     */
    public function getCreatedAtHumanAttribute()
    {
        return Format::date($this->created_at, 'datetime human');
    }

    /**
     * 获取友好的文件大小
     *
     * @return string
     */
    public function getSizeHumanAttribute()
    {
        return $this->size ? Format::size($this->size) : null;
    }

    /**
     * 获取友好的图标
     *
     * @return string
     */
    public function getIconAttribute()
    {
        return app('files')->icon($this->extension, $this->type);
    }

    /**
     * 判定文件类型
     * @param  mixed $type 类型
     * @return boolean
     */
    public function isType($type)
    {
        // 根据类型判断
        if ($this->type == $type) {
            return true;
        }

        return false;
    }    

    /**
     * Dynamically pass missing methods to the user.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        // isAdmin,isSuper,isMember
        if (starts_with($method, 'is')) {
            return $this->isType(strtolower(substr($method, 2)));
        }

        return parent::__call($method, $parameters);
    }    
}
