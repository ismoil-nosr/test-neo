<?php

namespace App\Http\Requests\Admin\News;

use App\Enums\NewsStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NewsUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'text' => 'required|string',
            'status' => 'required|string|in:' . implode(',', NewsStatusEnum::values()),
            'image' => 'image|mimes:jpg,png,jpeg,gif|max:2048'
        ];
    }
}
