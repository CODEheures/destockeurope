<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompagnyNameRequest extends FormRequest
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
            'value' => 'required|min:'.config('db_limits.users.minCompagnyName').'|max:'.config('db_limits.users.maxCompagnyName'),
        ];
    }

    public function messages()
    {
        return [
            'value.required' => trans('strings.request_input_require', ['name' => trans('strings.view_user_account_compagny_name_label')]),
            'value.min' => trans('strings.request_input_min_chars', ['name' => trans('strings.view_user_account_compagny_name_label'), 'min' => config('db_limits.users.minCompagnyName')]),
            'value.max' => trans('strings.request_input_max_chars', ['name' => trans('strings.view_user_account_compagny_name_label'), 'max' => config('db_limits.users.maxCompagnyName')])
        ];
    }
}
