<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlantFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['nullable','string'],
            'q' => ['nullable','string','max:100'],
            'min_price' => ['nullable','numeric','min:0'],
            'max_price' => ['nullable','numeric','min:0'],
            'sort' => ['nullable','in:latest,price_asc,price_desc'],
            'page' => ['nullable','integer','min:1'],
        ];
    }
}



