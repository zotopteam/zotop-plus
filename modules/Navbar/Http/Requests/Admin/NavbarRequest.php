<?php

namespace Modules\Navbar\Http\Requests\Admin;

use App\Modules\Http\FormRequest;
use Illuminate\Http\Request;

class NavbarRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'title'  => 'nullable|string|max:200',
            'slug'   => 'nullable|string|max:200',
            'sort'   => 'required|integer|min:0',
            'status' => 'required|integer',
        ];

        //添加时
        if ( $request->isMethod('POST') ) {
            $rules = [];
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            $rules = [];
        }

        return $rules;
    }

    /**
     * 自定义错误消息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title'  => trans('navbar::navbar.title.help'),
            'slug'   => trans('navbar::navbar.slug.help'),
            'sort'   => trans('navbar::navbar.sort.help'),
            'status' => trans('navbar::navbar.status.help'),
        ];
    }

    /**
     * 自定义错误消息中的标签
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title'  => trans('navbar::navbar.title.label'),
            'slug'   => trans('navbar::navbar.slug.label'),
            'sort'   => trans('navbar::navbar.sort.label'),
            'status' => trans('navbar::navbar.status.label'),
        ];
    }
}
