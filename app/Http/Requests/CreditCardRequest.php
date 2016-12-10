<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
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
            'name' => 'required|min:3',
            'card_no' => 'required|numeric|min:14|max:14',
            'cvc' => 'required|numeric|min:3',
            'expiration_month' => 'required',
            'expiration_year' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'value.required' => trans('strings.request_input_require'),
            'value.min' => trans('strings.request_input_min_chars', ['min' => config('db_limits.users.minName')])
        ];
    }
}
