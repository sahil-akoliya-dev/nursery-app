<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'line1' => ['required','string','max:255'],
            'line2' => ['nullable','string','max:255'],
            'city' => ['required','string','max:120'],
            'state' => ['required','string','max:120'],
            'postal_code' => ['required','string','max:20'],
            'country' => ['required','string','max:120'],
            'payment_method' => ['required','in:cod,card,upi,netbanking'],
        ];
    }
}


