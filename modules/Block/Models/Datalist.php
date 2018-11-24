<?php

namespace Modules\Block\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Traits\UserRelation;
use Modules\Block\Models\Block;

class Datalist extends Model
{
    use UserRelation;
   
    protected $table = 'block_datalist';
    protected $fillable = ['block_id','data_id','module','data','user_id','sort','stick','status'];

    /**
     * 属性转换
     *
     * @var array
     */
    protected $casts = [
        'data'   => 'json'
    ];

    /**
     * 全局作用域
     * 
     * @return null
     */
    protected static function boot()
    {
        parent::boot();

        // sort
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderby('stick', 'desc')->orderby('sort', 'desc');
        });

        // 保存前
        static::creating(function ($model) {
            $model->status = 'publish';
            $model->sort   = time();
        });        

        // 保存后
        static::saved(function ($model) {
            static::updateBlockData($model->block_id);
        });        
    }   

    /**
     * 获取字段信息
     * 
     * @param  array $block_fields 区块字段设置
     * @param  array  $data        区块数据
     * @return array
     */
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

            // 如果是上传字段
            if (in_array($field['type'], ['upload', 'upload_image', 'upload_file'])) {
                $field['data_id'] = $data['data_id'];
            }

            // 如果是图片字段
            if (in_array($field['type'], ['upload_image'])) {
                $resize = array_pull($field, 'resize');
                if ($resize['type'] == 'origin') {
                    $field['resize'] = false;
                } elseif ($resize['type'] == 'system') {
                    $field['resize'] = true;
                } elseif ($resize['type'] == 'thumb') {
                    $field['resize'] = ['width'=>$resize['width'],'height'=>$resize['height']];
                } elseif ($resize['type'] == 'crop') {
                    $field['resize'] = ['width'=>$resize['width'],'height'=>$resize['height'],'crop'=>true];
                }
            }            

            // 重组字段数据
            $fields[] = [
                'label' => $label,
                'field' => $field,
                'help'  => $help,
            ];
        }

        return $fields;
    }

    /**
     * 获取已经发布的数据
     * 
     * @param  int $block_id 区块编号
     * @return collection
     */
    public static function history($block_id)
    {
        $history = static::where('status', 'history')->where('block_id', $block_id)->get();
        
        return $history;
    }

    /**
     * 获取已经发布的数据
     * 
     * @param  int $block_id 区块编号
     * @return collection
     */
    public static function publish($block_id)
    {
        $publish = static::with('user')->where('status', 'publish')->where('block_id', $block_id)->get();
        
        return $publish;
    }

    /**
     * 更新区块数据
     * 
     * @param  int $block_id 区块编号
     * @return bool
     */
    public static function updateBlockData($block_id)
    {
        // 获取区块信息
        $block = Block::findOrFail($block_id);

        // 获取发布的数据
        $publish = static::publish($block->id);

        // 如果限定行数，将超出部分设为history，如果该数据被置顶，取消置顶
        if ($block->rows && $publish->count() > $block->rows) {
            $history = $publish->splice($block->rows);
            static::whereIn('id', $history->pluck('id'))->update([
                'status' => 'history',
                'stick'  => 0,
            ]);
        }

        // 更新block的data数据
        Block::where('id', $block_id)->update([
            'data' => $publish->pluck('data')
        ]);

        return true;
    }

    /**
     * 区块数据标题
     *
     * @param  string  $value
     * @return string
     */
    public function getTitleAttribute($value)
    {        
        return $this->data['title'] ?? '';
    }

    /**
     * 区块数据图片预览
     *
     * @param  string  $value
     * @return string
     */
    public function getImagePreviewAttribute($value)
    {
        $image = $this->data['image'] ?? '';

        // 如果有图片，返回图片路径，用于预览
        if ($image) {
            return public_path($image);
        }

        return '';
    }

    /**
     * 不更新时间戳
     * @return this
     */
    public function scopeWithoutTimestamps()
    {
        $this->timestamps = false;
        return $this;
    }            
}
