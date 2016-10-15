<?php

namespace App\Http\Requests;

use App\Common\DBUtils;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

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
        $this->sanitize();

        $inType = DBUtils::getEnumValues('adverts', 'type');
        $line='';
        foreach ($inType as $key => $item) {
            if ($line == '') {
                $line = $item;
            } else {
                $line = $line . ',' . $item;
            }

        }

        return [
            'category' => 'required|numeric|exists:categories,id',
            'type' => 'required|in:'.$line,
            'title' => 'required|min:'. config('db_limits.adverts.minTitle') . '|max:'. config('db_limits.adverts.maxTitle') ,
            'description' => 'required|min:' . config('db_limits.adverts.minDescription') . '|max:' . config('db_limits.adverts.maxDescription'),
            'price' => 'required|numeric|min:0.01',
        ];
    }

    public function sanitize() {
        $input = $this->all();
        $input['price'] = (int)  ($input['price']*100);
        $this->replace($input);
    }
}
