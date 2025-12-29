<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage plants') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('category');
        return [
            'name' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:categories,slug,'.$id],
            'description' => ['nullable','string'],
        ];
    }
}



