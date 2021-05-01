<?php

namespace Zotop\Modules\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    /**
     * api 验证时候捕获第一个错误返回
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @author Chen Lei
     * @date 2021-01-14
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code'    => 422,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
