<?php

namespace App\Http\Requests;

use App\Common\DBUtils;
use App\Common\PicturesManager;
use App\Http\Controllers\UtilsController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UpdateRegistrationRequest extends FormRequest
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
            'value' => 'required|min:'. config('db_limits.users.minRegistrationNumber') .'|max:'.config('db_limits.users.maxRegistrationNumber'),
        ];
    }

    public function messages()
    {
        return [
            'value.required' => trans('strings.request_input_require', ['name' => trans('strings.view_user_account_compagny_number_label')]),
            'value.min' => trans('strings.request_input_min_chars', ['name' => trans('strings.view_user_account_compagny_number_label'), 'min' => config('db_limits.users.minRegistrationNumber')]),
            'value.max' => trans('strings.request_input_max_chars', ['name' => trans('strings.view_user_account_compagny_number_label'), 'max' => config('db_limits.users.maxRegistrationNumber')])
        ];
    }
}
