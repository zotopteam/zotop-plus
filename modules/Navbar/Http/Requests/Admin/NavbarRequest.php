<?php

namespace Modules\Navbar\Http\Requests\Admin;

use App\Modules\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Validation\Rule;

class NavbarRequest extends FormRequest
{
    /**
     * FieldRequest constructor.
     *
     * @param \Illuminate\Validation\Factory $validationFactory
     */
    public function __construct(ValidationFactory $validationFactory)
    {
        parent::__construct();

        $validationFactory->extend('slug', function ($attribute, $value, $parameters) {
            return preg_match('/^[a-z]+[a-z0-9_-]+[a-z0-9]+$/', $value);
        }, trans('navbar::navbar.slug.help'));
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
            'title' => 'required|string|max:200',
            'slug'  => ['required', 'string', 'max:200', 'slug'],
            'sort'  => 'required|integer|min:0',
        ];

        //添加时
        if ($request->isMethod('POST')) {
            $rules['slug'][] = Rule::unique('navbar');
        }

        // 修改时
        if ($request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $rules['slug'][] = Rule::unique('navbar')->ignore($this->route('id'));
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
            'title' => trans('navbar::navbar.title.help'),
            'slug'  => trans('navbar::navbar.slug.help'),
            'sort'  => trans('navbar::navbar.sort.help'),
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
            'title' => trans('navbar::navbar.title.label'),
            'slug'  => trans('navbar::navbar.slug.label'),
            'sort'  => trans('navbar::navbar.sort.label'),
        ];
    }
}
