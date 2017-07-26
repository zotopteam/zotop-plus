<?php

namespace Modules\Developer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Module;
use Illuminate\Validation\Factory as ValidationFactory;

class ModuleRequest extends FormRequest
{
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('module_exists',function ($attribute, $value, $parameters) {
            return !Module::has(Str::studly($value));
        },trans('core::master.existed'));
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
                'name'  => 'required|alpha|module_exists',
                'plain' => 'required|integer'
            ];
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $name = $this->route('name');

            return [

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
            'name'         => trans('developer::module.name.label'),
            'type'         => trans('developer::module.type.label'),
        ];
    }      
}
