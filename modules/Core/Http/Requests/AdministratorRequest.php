<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Modules\Core\Models\User;

class AdministratorRequest extends FormRequest
{
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
                'username'         => 'required|unique:users',
                'password'         => 'required|min:6', 
                'password_confirm' => 'required|same:password',
                'roles'            => 'required',                
                'nickname'         => 'required|max:100|unique:users',
                'email'            => 'required|unique:users',
                'mobile'           => 'required|unique:users',
            ];
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $id = $this->route('id');

            return [
                'username'         => 'required|unique:users,username,'.$id.',id',
                'password_new'     => 'min:6', 
                'password_confirm' => 'same:password_new',
                'roles'            => User::find($id)->isSuper() ? '' : 'required',
                'nickname'         => 'required|max:100|unique:users,nickname,'.$id.',id',
                'email'            => 'required|unique:users,email,'.$id.',id', 
                'mobile'           => 'required|unique:users,mobile,'.$id.',id',    
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
            'username'         => trans('core::administrator.username.label'),
            'password'         => trans('core::administrator.password.label'),
            'password_confirm' => trans('core::administrator.password_confirm.label'),
            'roles'            => trans('core::administrator.roles.label'),
            'nickname'         => trans('core::administrator.nickname.label'),
            'email'            => trans('core::administrator.email.label'),
            'mobile'           => trans('core::administrator.mobile.label'), 
        ];
    }      
}
