<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;

class ModelRequest extends FormRequest
{
    // 初始化
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('check_id',function ($attribute, $value, $parameters) {
            return preg_match('/^[a-z]+[a-z0-9_]+[a-z0-9]+$/', $value);
        }, trans('content::model.id.regex'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        //添加时
        if ( $request->isMethod('POST') ) {

            return [
                'id'       => 'required|check_id|max:64|unique:content_model',
                'name'     => 'required|max:64',
                'template' => 'required|max:100', 
            ];
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $id = $this->route('id');

            return [
                'id'       => 'required|check_id|max:64|unique:content_model,id,'.$id.',id',
                'name'     => 'required|max:64',
                'template' => 'required|max:100', 
            ];
        }

        return [];  
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
            'id'       => trans('content::model.id.label'),
            'name'     => trans('content::model.name.label'),
            'template' => trans('content::model.template.label'),
        ];
    }      
}
