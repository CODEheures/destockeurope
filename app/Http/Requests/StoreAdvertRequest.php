<?php

namespace App\Http\Requests;

use App\Common\DBUtils;
use App\Http\Controllers\UtilsController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class StoreAdvertRequest extends FormRequest
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

        $inType = DBUtils::getEnumValues('adverts', 'type');
        $line='';
        foreach ($inType as $key => $item) {
            if ($line == '') {
                $line = $item;
            } else {
                $line = $line . ',' . $item;
            }

        }


        $currencies = new ISOCurrencies();
        $listCodeCurrencies=[];
        foreach ($currencies as $currency) {
            $listCodeCurrencies[$currency->getCode()] = $currency->getCode();
        }

        $line2='';
        foreach ($listCodeCurrencies as $key => $currency) {
            if ($line2 == '') {
                $line2 = $key;
            } else {
                $line2 = $line2 . ',' . $key;
            }

        }

        return [
            'type' => 'required|in:'.$line,
            'category' => 'required|numeric|exists:categories,id',
            'title' => 'required|min:'. config('db_limits.adverts.minTitle') . '|max:'. config('db_limits.adverts.maxTitle') ,
            'description' => 'required|min:' . config('db_limits.adverts.minDescription') . '|max:' . config('db_limits.adverts.maxDescription'),
            'price' => 'required|numeric|min:0.01',
            'currency' => 'required|in:'.$line2,
            'lat' => 'required|numeric|min:-90|max:90',
            'lng' => 'required|numeric|min:-180|max:180',
            'geoloc' => 'required|min:2'
        ];
    }
}
