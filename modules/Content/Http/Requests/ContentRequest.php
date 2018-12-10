<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Modules\Content\Models\Field;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
{
    // 初始化
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('slug',function ($attribute, $value, $parameters) {
            return preg_match('/^[a-z0-9]+[a-z0-9-]+[a-z0-9]+$/', $value);
        }, trans('content::content.slug.regex'));
    }

    /**
     * 获取字段信息
     * @return [type] [description]
     */
    private function fields()
    {
        static $fields;

        if (empty($fields)) {
            $model_id = $this->input('model_id');

            if (empty($model_id)) {
                abort(404,'model id required');
            }
            $types  = Field::types($model_id);
            $fields = Field::where('model_id', $model_id)->get()->transform(function($field) use($types) {
                
                // 合并默认设置
                if ($settings = array_get($types, $field->type.'.settings')) {
                    $field->settings = array_merge($settings, $field->settings);
                }

                // 补充字段创建方法名称 (对应laravel的数据迁移方法名称)
                $field->method = array_get($types, $field->type.'.method');

                return $field;              
            });
        }

        return $fields;
    } 

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        
        $fields = $this->fields();

        $rules = [];

        foreach ($fields as $field) {
            $rule = [];
            $name = $field->name;

            if (in_array($field->method, ['integer','string'])) {
                $rule[] = $field->method;
            }

            if (in_array($field->method, ['date','datetime'])) {
                $rule[] = 'date';
            }            

            if (in_array($field->type, ['email','url','slug'])) {
                $rule[] = $field->type;
            }               

            foreach($field->settings as $key=>$val) {

                if ($key == 'required') {
                    $rule[] = $val ? $key : 'nullable';
                }

                if (in_array($key, ['min','minlength'])) {
                    $rule[] = 'min:'.intval($val);
                }

                if (in_array($key, ['max','maxlength'])) {
                    $rule[] = 'max:'.intval($val);
                }

                if ($key == 'unique') {
                    if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
                        $rule[] = Rule::unique('content')->ignore($this->route('id'));
                    } else {
                        $rule[] = Rule::unique('content');
                    }                   
                }
            }

            if ($rule) {
                $rules[$name] = $rule;
            }
        }

        debug($rules);

        //添加时
        if ( $request->isMethod('POST') ) {
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $id = $this->route('id');
        }

        return $rules;  
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 自定义错误消息中的标签
     * 
     * @return array
     */
    public function attributes()
    {
        return $this->fields()->pluck('label','name')->toArray();
    }      
}
