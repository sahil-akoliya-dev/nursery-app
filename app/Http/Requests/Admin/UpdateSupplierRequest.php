<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage plants') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'contact_person' => ['nullable','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string'],
        ];
    }
}


