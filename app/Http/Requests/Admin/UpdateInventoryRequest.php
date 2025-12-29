<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage plants') ?? false;
    }

    public function rules(): array
    {
        return [
            'plant_id' => ['required','exists:plants,id'],
            'supplier_id' => ['nullable','exists:suppliers,id'],
            'batch_number' => ['nullable','string','max:100'],
            'quantity' => ['required','integer','min:1'],
            'purchase_price' => ['nullable','numeric','min:0','max:999999.99'],
            'expiry_date' => ['nullable','date'],
        ];
    }
}


