<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class BookItemRequest extends FormRequest
{

    use ErrorJsonTrait;
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
            'first_name'=>'required|string|min:2',
            'last_name'=>'string|min:2',
            'phone'=>'required|regex:/^[+][0-9]/|min:10',
            'country_code'=>'required|string',
            'timezone_name'=>'required|string'
        ];
    }

}
