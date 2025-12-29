<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reviewable_type' => 'required|string|in:product,plant',
            'reviewable_id' => 'required|integer|min:1',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:5000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize HTML content
        if ($this->has('content')) {
            $this->merge([
                'content' => strip_tags($this->content, '<p><br><strong><em>'),
            ]);
        }

        if ($this->has('title')) {
            $this->merge([
                'title' => htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8'),
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'reviewable_type.required' => 'Review type is required.',
            'reviewable_id.required' => 'Item ID is required.',
            'rating.required' => 'Rating is required.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot exceed 5.',
            'content.required' => 'Review content is required.',
            'content.min' => 'Review content must be at least 10 characters.',
            'content.max' => 'Review content cannot exceed 5000 characters.',
            'images.max' => 'Maximum 5 images allowed.',
            'images.*.image' => 'All files must be images.',
            'images.*.max' => 'Each image must be less than 2MB.',
        ];
    }
}

