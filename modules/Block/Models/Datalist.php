<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Traits\UserRelation;

class Datalist extends Model
{
    use UserRelation;
   
    protected $table = 'block_datalist';
    protected $fillable = ['block_id','resource_id','module','data','user_id','sort','stick','status'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'data'   => 'json'
    ];

    public static function fields($block_fields, $data=[])
    {
        $fields = [];

        foreach ($block_fields as $key => $field) {

            // 取出非字段标签
            $show  = array_pull($field, 'show');
            $label = array_pull($field, 'label');
            $help  = array_pull($field, 'help');

            // 重组字段数据
            $field['id']   = 'data_'.$field['name'];
            $field['name'] = 'data['.$field['name'].']';

            // 重组字段数据
            $fields[] = [
                'label' => $label,
                'field' => $field,
                'help'  => $help,
            ];
        }

        return $fields;
    }
}
