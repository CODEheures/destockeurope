<?php

namespace App\Http\Requests;

use App\Common\PicturesManager;
use Codeheures\LaravelUtils\Traits\Tools\Database;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Money\Currencies\ISOCurrencies;

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

        $inType = Database::getEnumValues('adverts', 'type');
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

        $picturesManager = new PicturesManager();
        $pictures = $picturesManager->listThumbs(PicturesManager::TYPE_TEMPO_LOCAL);
        $line3='';
        foreach ($pictures as $picture) {
            if ($line3 == '') {
                $line3 = $picture;
            } else {
                $line3 = $line3 . ',' . $picture;
            }

        }

        return [
            'type' => 'required|in:'.$line,
            'category' => 'required|numeric|exists:categories,id',
            'title' => 'required|min:'. config('db_limits.adverts.minTitle') . '|max:'. config('db_limits.adverts.maxTitle') ,
            'manu_ref' => 'nullable|string',
            'description' => 'required|min:' . config('db_limits.adverts.minDescription') . '|max:' . config('db_limits.adverts.maxDescription'),
            'price' => 'required_if:is_negociated,false|numeric|min:0.001',
            'discount_on_total' => 'required|numeric|min:0|max:100',
            'currency' => 'required|in:'.$line2,
            'lat' => 'required|numeric|min:-90|max:90',
            'lng' => 'required|numeric|min:-180|max:180',
            'geoloc' => 'required|min:2',
            'main_picture' => 'required|size:32|in:'.$line3,
            'total_quantity' => 'required|numeric|min:1',
            'lot_mini_quantity' => 'required|numeric|min:1',
            'is_urgent' => 'required|boolean',
            'is_negociated' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'main_picture.required' => trans('strings.request_main_picture_require'),
            'price.min' => trans('strings.request_min_price')
        ];
    }
}
