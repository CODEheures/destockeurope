<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

class PictureAdvertRequest extends FormRequest
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

        $maxSize = env('FILE_MAX_SIZE')*1024;

        return [
            'addpicture' => 'required|image|min:2|max:'.$maxSize.'|dimensions:min_width=100,min_height=200'
        ];
    }
}
