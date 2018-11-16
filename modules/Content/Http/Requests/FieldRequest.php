<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\Rule;


class FieldRequest extends FormRequest
{
    // 初始化
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('check_name',function ($attribute, $value, $parameters) {
            return preg_match('/^[a-z]+[a-z0-9_]+[a-z0-9]+$/', $value);
        }, trans('content::field.name.regex'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'model_id' => 'required|max:64',
            'label'    => 'required|max:100',
            'type'     => 'required|max:100',
            'name'     => ['required','max:64','check_name']
        ];

        //添加时当前模型名称唯一
        if ( $request->isMethod('POST') ) {
            $rules['name'][] = Rule::unique('content_field')->where(function ($query) use ($request) {
                return $query->where('model_id', $request->model_id);
            });
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $id = $this->route('id');

            $rules['name'][] = Rule::unique('content_field')->where(function ($query) use ($request) {
                return $query->where('model_id', $request->model_id);
            })->ignore($id);         

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
        return [
            'label' => trans('content::field.label.label'),
            'name'  => trans('content::field.name.label'),
            'type'  => trans('content::field.type.label'),
        ];
    }      
}
