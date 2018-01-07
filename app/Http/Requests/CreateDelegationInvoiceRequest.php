<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class CreateDelegationInvoiceRequest extends FormRequest
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
            'customer' => 'required|string|min:2|max:255',
            'description' => 'required|string|min:2|max:255',
            'date' => 'required|date_format:Y-m-d',
            'marge' => 'required|Numeric|min:1'
        ];
    }
}
