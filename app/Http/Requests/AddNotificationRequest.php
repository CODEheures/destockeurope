<?php

namespace App\Http\Requests;

use App\Common\DBUtils;
use App\Common\PicturesManager;
use App\Http\Controllers\UtilsController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class AddNotificationRequest extends FormRequest
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
            'token' => 'required|string',
            'topic_id' => 'required|integer|min:1'
        ];
    }
}
