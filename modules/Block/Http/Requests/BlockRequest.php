<?php

namespace Modules\Block\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Modules\Core\Models\User;

class BlockRequest extends FormRequest
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
                'type'        => 'required',
                'category_id' => 'required',
                'slug'        => 'required|max:64|unique:block',
                'view'    => 'required',
            ];
        }

        // 修改时
        if ( $request->isMethod('PUT')  || $request->isMethod('PATCH') )  {
            
            $id = $this->route('id');

            return [
                'type'        => 'required',
                'category_id' => 'required',
                'slug'        => 'required|max:64|unique:block,slug,'.$id.',id',
                'view'    => 'required',   
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
            'type'        => trans('block::block.type'),
            'category_id' => trans('block::block.category_id'),
            'slug'        => trans('block::block.slug'),
            'view'    => trans('block::block.view'),
        ];
    }      
}
