<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class ConfigBaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // 修改时
        if ( $request->isMethod('POST') )
        {
            return [
                'name'  => 'required|max:100',
                'theme' => 'required',  
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
        return [];
    }      
}
