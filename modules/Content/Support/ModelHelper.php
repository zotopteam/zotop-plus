<?php
namespace Modules\Content\Support;

use Modules\Content\Models\Model;
use Modules\Content\Models\Field;
use File;
use Module;

class ModelHelper
{

	/**
	 * 过滤器钩子触发
	 * 
	 * @param  string $hook  钩子名称
	 * @param  mixed $param  值
	 * @return mixed         总是返$param
	 */
	public static function getImport($exclude = [])
	{
        $import = collect([]);

        // 获取未导入的模型
        foreach (File::files(realpath(__DIR__.'/../Database/Models/')) as $file) {
            $data = json_decode(File::get($file));

            if (isset($data->model->id) && !in_array($data->model->id, $exclude)) {
                $import->put(path_base($file), $data->model);
            }
        }

        return $import;        
	}

    /**
     * 导出模型
     * @param  string $id 模型标识
     * @return download
     */
    public static function export($id)
    {
        $model = Model::findOrFail($id);

        // 导出的内容
        $content = [
            'model'    => $model->toArray(),
            'field'    => [],
            'template' => ''
        ];

        // 写入临时文件
        $file = tap(storage_path("temp/{$id}.model"), function($file) use ($content) {
            app('files')->put($file, json_encode($content));
        }); 

        return response()->download($file)->deleteFileAfterSend(true);        
    }

    /**
     * 导入模型
     * 
     * @param  string $file 模型文件路径
     * @return bool
     */
    public static function import($file, $override=false)
    {
        $data = json_decode(File::get($file), true);

        if (empty($data) || !isset($data['model']['id'])) {
            abort(500, 'Incorrect model file');
        }

        // 检查模型是否存在
        if (Model::where('id', $data['model']['id'])->count() > 0) {

            if (! $override) {
                abort(500, trans('core::master.existed', [$data['model']['id']]));
            }
            
            // 删除
        }

        // 导入模型主体数据
        $model = new Model;
        $model->fill($data['model']);
        $model->sort = Model::max('sort') + 1;
        $model->save();

        return true;
    }

    /**
     * 初始化
     * @param  [type] $modelId [description]
     * @return [type]          [description]
     */
    public static function fieldInit($modelId)
    {
        $system = Module::data('content::field.system');
        $types  = Module::data('content::field.types');

        Field::where('model_id', $modelId)->delete();

        // 插入模块的系统字段，合并字段默认设置
        foreach ($system as $field) {
            Field::create(array_merge($field, [
                'model_id' => $modelId,
                'settings' => array_get($types, $field['type'].'.settings', []),
                'system'   => 1,
            ]));
        }

    }
}
