<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plant_id' => ['required','exists:plants,id'],
            'quantity' => ['required','integer','min:1','max:99'],
        ];
    }
}


