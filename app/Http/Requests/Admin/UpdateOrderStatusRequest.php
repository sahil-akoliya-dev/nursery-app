<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage orders') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required','in:pending,paid,shipped,completed,cancelled'],
        ];
    }
}


