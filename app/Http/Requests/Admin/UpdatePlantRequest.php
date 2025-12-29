<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage plants') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('plant');
        return [
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:255'],
            'scientific_name' => ['nullable','string','max:255'],
            'common_name' => ['nullable','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:plants,slug,'.$id],
            'description' => ['nullable','string'],
            'care_instructions' => ['nullable','array'],
            'care_instructions.watering' => ['nullable','string','max:50'],
            'care_instructions.sunlight' => ['nullable','string','max:50'],
            'care_instructions.fertilizer' => ['nullable','string','max:50'],
            'price' => ['required','numeric','min:0','max:999999.99'],
            'stock_quantity' => ['required','integer','min:0'],
            'is_featured' => ['nullable','boolean'],
            'status' => ['required','in:active,inactive'],
            'images' => ['nullable','array'],
            'images.*' => ['image','mimes:jpeg,png,jpg,webp','max:4096'],
        ];
    }
}



