<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Factory as ValidationFactory;

class MinePasswordRequest extends FormRequest
{
    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend('check_password',function ($attribute, $value, $parameters) {
            return \Hash::check($value, Auth::user()->password);
        },trans('core::mine.password_old.wrong'));
    }

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
            return [
                'password_old'     => 'required|check_password',
                'password_new'     => 'required|min:6', 
                'password_confirm' => 'required|same:password_new',    
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
            'password_old'     => trans('core::mine.password_old.label'),
            'password_new'     => trans('core::mine.password_new.label'),
            'password_confirm' => trans('core::mine.password_confirm.label'), 
        ];
    }      
}
