<?php

namespace Modules\Navbar\Http\Requests\Admin;

use App\Modules\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\Rule;


class FieldRequest extends FormRequest
{
    /**
     * FieldRequest constructor.
     *
     * @param \Illuminate\Validation\Factory $validationFactory
     */
    public function __construct(ValidationFactory $validationFactory)
    {
        parent::__construct();

        $validationFactory->extend('check_name', function ($attribute, $value, $parameters) {
            return preg_match('/^[a-z]+[a-z0-9_]+[a-z0-9]+$/', $value);
        }, trans('navbar::field.name.regex'));
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
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'navbar_id' => 'required|integer',
            'parent_id' => 'required|integer',
            'label'     => 'required|string|max:100',
            'type'      => 'required|string|max:100',
            'name'      => ['required', 'string', 'max:100', 'check_name'],
            'default'   => 'nullable|string',
            'settings'  => 'nullable|array',
            'help'      => 'nullable|string|max:255',
            'sort'      => 'nullable|integer|min:0',
            'disabled'  => 'nullable|integer',
        ];


        //添加时当前模型名称唯一
        if ($request->isMethod('POST')) {
            $rules['name'][] = Rule::unique('navbar_field')->where(function ($query) use ($request) {
                return $query->where('navbar_id', $request->navbar_id)->where('parent_id', $request->parent_id);
            });
        }

        // 修改时
        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {

            $id = $this->route('id');

            $rules['name'][] = Rule::unique('navbar_field')->where(function ($query) use ($request) {
                return $query->where('navbar_id', $request->navbar_id)->where('parent_id', $request->parent_id);
            })->ignore($id);

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
            'navbar_id' => trans('navbar::field.navbar_id.help'),
            'parent_id' => trans('navbar::field.parent_id.help'),
            'label'     => trans('navbar::field.label.help'),
            'type'      => trans('navbar::field.type.help'),
            'name'      => trans('navbar::field.name.help'),
            'default'   => trans('navbar::field.default.help'),
            'settings'  => trans('navbar::field.settings.help'),
            'help'      => trans('navbar::field.help.help'),
            'sort'      => trans('navbar::field.sort.help'),
            'disabled'  => trans('navbar::field.disabled.help'),
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
            'navbar_id' => trans('navbar::field.navbar_id.label'),
            'parent_id' => trans('navbar::field.parent_id.label'),
            'label'     => trans('navbar::field.label.label'),
            'type'      => trans('navbar::field.type.label'),
            'name'      => trans('navbar::field.name.label'),
            'default'   => trans('navbar::field.default.label'),
            'settings'  => trans('navbar::field.settings.label'),
            'help'      => trans('navbar::field.help.label'),
            'sort'      => trans('navbar::field.sort.label'),
            'disabled'  => trans('navbar::field.disabled.label'),
        ];
    }
}
