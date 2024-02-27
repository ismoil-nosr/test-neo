<?php

namespace App\Http\Requests\Admin\User;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|int|unique:users,phone,' . request()->route('userId'),
            'email' => 'required|string|email|max:255|unique:users,email,' . request()->route('userId'),
            'password' => 'string|min:8|confirmed',
            'status' => 'required|string|in:' . implode(',', UserStatusEnum::values()),
            'role' => 'required|string|in:' . implode(',', UserRoleEnum::values())
        ];
    }
}
