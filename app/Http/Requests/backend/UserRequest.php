<?php

namespace App\Http\Requests\backend;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        switch ($this->method()) {
            case 'POST': {
                    return [
                        'name' => ['required', 'string', 'max:255'],
                        'username' => ['required', 'string', 'max:255', 'unique:users'],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'phone' => ['nullable', 'string', 'max:20'],
                        'country' => ['nullable', 'string', 'max:100'],
                        'city' => ['nullable', 'string', 'max:100'],
                        'street' => ['nullable', 'string', 'max:100'],
                        'gender' => ['required', 'in:0,1'], // 0 = Male, 1 = Female
                        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                    ];
                }
            case 'PUT':
            case 'PATCH': {

                    return [
                        'name' => ['nullable', 'string', 'max:255'],
                        'username' => ['required', 'string', 'max:255', 'unique:users,username,' .  auth()->id()],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
                        'phone' => ['nullable', 'string', 'max:20'],
                        'country' => ['nullable', 'string', 'max:100'],
                        'city' => ['nullable', 'string', 'max:100'],
                        'street' => ['nullable', 'string', 'max:100'],
                        'gender' => ['nullable', 'in:0,1'],
                        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                    ];
                }
            default:
                break;
        }
    }
}
