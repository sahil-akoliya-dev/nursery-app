<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxSize = 2 * 1024; // 2MB in KB
        $maxImages = 5;

        if ($this->has('images')) {
            // Multiple images (array)
            return [
                'images.*' => [
                    'required',
                    'image',
                    'mimes:jpeg,jpg,png,gif,webp',
                    'max:' . $maxSize,
                    Rule::dimensions()->maxWidth(5000)->maxHeight(5000),
                ],
                'images' => [
                    'array',
                    'max:' . $maxImages,
                ],
            ];
        }

        // Single image
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:' . $maxSize,
                Rule::dimensions()->minWidth(100)->minHeight(100)->maxWidth(5000)->maxHeight(5000),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.required' => 'An image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, jpg, png, gif, webp.',
            'image.max' => 'The image must not be larger than 2MB.',
            'images.*.required' => 'Each image is required.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, jpg, png, gif, webp.',
            'images.*.max' => 'Each image must not be larger than 2MB.',
            'images.max' => 'Maximum ' . $this->rules()['images']['max'] ?? 5 . ' images allowed.',
        ];
    }
}

