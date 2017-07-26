<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class MineRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )
        {
            
            $id = Auth::user()->id;

            return [
                'nickname' => 'required|max:100|unique:users,nickname,'.$id.',id',
                'email'    => 'required|unique:users,email,'.$id.',id', 
                'mobile'   => 'required|unique:users,mobile,'.$id.',id',    
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
            'nickname' => trans('core::mine.nickname.label'),
            'email'    => trans('core::mine.email.label'),
            'mobile'   => trans('core::mine.mobile.label'), 
        ];
    }      
}
