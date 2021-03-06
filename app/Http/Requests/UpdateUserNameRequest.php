<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserNameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Guard $auth)
    {
        return $auth->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'required|min:'.config('db_limits.users.minName'),
        ];
    }

    public function messages()
    {
        return [
            'value.required' => trans('strings.request_input_require', ['name' => trans('strings.form_label_name')]),
            'value.min' => trans('strings.request_input_min_chars', ['name' => trans('strings.form_label_name'), 'min' => config('db_limits.users.minName')])
        ];
    }
}
