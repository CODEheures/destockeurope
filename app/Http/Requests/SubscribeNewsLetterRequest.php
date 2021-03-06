<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeNewsLetterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'name' => 'nullable|min:'.config('db_limits.users.minName'),
            'phone' => 'nullable|max:'.config('db_limits.users.maxPhone'),
        ];
    }

    public function messages()
    {
        return [
            'email.required' => trans('strings.request_input_require', ['name' => trans('strings.form_label_email')]),
            'name.min' => trans('strings.request_input_min_chars', ['name' => trans('strings.form_label_name'), 'min' => config('db_limits.users.minName')]),
            'phone.max' => trans('strings.request_input_max_chars', ['name' => trans('strings.form_label_phone'), 'max' => config('db_limits.users.maxPhone')])
        ];
    }
}
