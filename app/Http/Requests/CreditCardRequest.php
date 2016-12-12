<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class CreditCardRequest extends FormRequest
{
    private $minCVC = 0;
    private $maxCVC = 999;
    private $minMonth = 1;
    private $maxMonth = 12;
    private $minYear;
    private $maxYear;

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
        $this->minYear = date('Y');
        $this->maxYear = $this->minYear+50;
        return [
            'card_type' => 'required|numeric|min:0|max:'. (count(config('paypal_cards.list'))-1),
            'name' => array('required', 'regex:/^[A-Za-z[:space:]]{1,255}$/'),
            'card_no' => 'required|numeric',
            'cvc' => 'required|numeric|min:' . $this->minCVC . '|max:' . $this->maxCVC,
            'expiration_month' => 'required|numeric|min:' . $this->minMonth . '|max:' . $this->maxMonth,
            'expiration_year' => 'required|numeric|min:' . $this->minYear . '|max:' . $this->maxYear
        ];
    }

    public function messages()
    {
        return [
            'card_type.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_type_label')]),
            'card_type.numeric' => trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_type_label')]),
            'card_type.min' => trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_type_label')]),
            'card_type.max' => trans('strings.request_input_generic_error', ['name' => trans('strings.payment_card_type_label')]),
            'name.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_name_label')]),
            'name.regex' => trans('strings.request_input_regex_name', ['name' => trans('strings.payment_card_name_label')]),
            'card_no.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_number_label')]),
            'card_no.numeric' => trans('strings.request_input_numeric', ['name' => trans('strings.payment_card_number_label')]),
            'cvc.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_cvc_label')]),
            'cvc.numeric' => trans('strings.request_input_numeric', ['name' => trans('strings.payment_card_cvc_label')]),
            'cvc.min' => trans('strings.request_input_min_numeric', ['name' => trans('strings.payment_card_cvc_label'), 'min' => $this->minCVC]),
            'cvc.max' => trans('strings.request_input_max_numeric', ['name' => trans('strings.payment_card_cvc_label'), 'max' => $this->maxCVC]),
            'expiration_month.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_month_placeholder')]),
            'expiration_month.numeric' => trans('strings.request_input_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_month_placeholder')]),
            'expiration_month.min' => trans('strings.request_input_min_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_month_placeholder'), 'min' => $this->minMonth]),
            'expiration_month.max' => trans('strings.request_input_max_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_month_placeholder'), 'max' => $this->maxMonth]),
            'expiration_year.required' => trans('strings.request_input_require', ['name' => trans('strings.payment_card_expiration_label'). ' ' . trans('strings.payment_card_expiration_year_placeholder')]),
            'expiration_year.numeric' => trans('strings.request_input_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_year_placeholder')]),
            'expiration_year.min' => trans('strings.request_input_min_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_year_placeholder'), 'min' => $this->minYear]),
            'expiration_year.max' => trans('strings.request_input_max_numeric', ['name' => trans('strings.payment_card_expiration_label') . ' ' . trans('strings.payment_card_expiration_year_placeholder'), 'max' => $this->maxYear]),
        ];
    }
}
