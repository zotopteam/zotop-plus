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
        $field = $model->field()->get();

        // 导出的内容
        $content = [
            'model'    => $model->toArray(),
            'field'    => $field->toArray(),
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

        foreach ($data['field'] as $field) {
            Field::create($field);
        }

        return true;
    }

    /**
     * 初始化
     * @param  string $model_id 模型编号
     * @return bool
     */
    public static function fieldInit($model_id)
    {   
        $types  = Field::types($model_id);
        $system = Module::data('content::field.system');
        
        // 插入模块的系统字段，合并字段默认设置
        foreach ($system as $field) {
            Field::create(array_merge($field, [
                'model_id' => $model_id,
                'settings' => array_get($types, $field['type'].'.settings', []),
                'system'   => 1,
            ]));
        }

        return true;
    }

    /**
     * 刷新模型扩展类
     * 
     * @param  model $model 模型实例
     * @return null
     */
    public static function refreshExtend($model)
    {
        if ($model->isDirty('id')) {
            app('files')->delete(dirname(__DIR__).'/Extend/'.studly_case($model->getOriginal('id')).'Model.php');
        }

        $path = dirname(__DIR__).'/Extend/'.studly_case($model->id).'Model.php';

        if ($model->table) {

            $model->fillable = "[".implode(", ", array_map(function($val) {
                 return "'".$val."'";
            }, $model->fillable))."]";

            $model->casts = "[".implode(',', array_map(function($val, $key) {
                return "'".$key."' => '".$val."'";
            }, $model->casts, array_keys($model->casts)))."]";

            $content = view('content::model.stub.extend')->with([
                'model' => $model,
            ])->render();

            app('files')->put($path, "<?php\r\n".$content);
        } else {
            app('files')->delete($path);
        }

        static::refreshExtendable();
    }

    /**
     * 刷新模型扩展关联关系
     * 
     * @return null
     */
    private static function refreshExtendable()
    {
        $path = dirname(__DIR__).'/Extend/Extendable.php';

        $models = Model::where('disabled', 0)->whereNotNull('table')->orderby('sort','asc')->get();

        $content = view('content::model.stub.extendable')->with([
            'models' => $models,
        ])->render();

        app('files')->put($path, "<?php\r\n".$content);
    }   
}
