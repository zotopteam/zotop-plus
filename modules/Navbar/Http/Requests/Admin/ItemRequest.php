<?php

namespace Modules\Navbar\Http\Requests\Admin;

use App\Modules\Http\FormRequest;
use Illuminate\Http\Request;

class ItemRequest extends FormRequest
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
            'navbar_id' => 'required|integer|min:0',
            'parent_id' => 'required|integer|min:0',
            'title'     => 'required|string|max:200',
            'link'      => 'nullable|string|max:200',
            'custom'    => 'nullable|string',
            'sort'      => 'required|integer|min:0',
            'status'    => 'required|integer',
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
            'navbar_id' => trans('navbar::item.navbar_id.help'),
            'parent_id' => trans('navbar::item.parent_id.help'),
            'title'     => trans('navbar::item.title.help'),
            'link'      => trans('navbar::item.link.help'),
            'custom'    => trans('navbar::item.custom.help'),
            'sort'      => trans('navbar::item.sort.help'),
            'status'    => trans('navbar::item.status.help'),
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
            'navbar_id' => trans('navbar::item.navbar_id.label'),
            'parent_id' => trans('navbar::item.parent_id.label'),
            'title'     => trans('navbar::item.title.label'),
            'link'      => trans('navbar::item.link.label'),
            'custom'    => trans('navbar::item.custom.label'),
            'sort'      => trans('navbar::item.sort.label'),
            'status'    => trans('navbar::item.status.label'),
        ];
    }
}
