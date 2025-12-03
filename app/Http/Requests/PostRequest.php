<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
    case 'POST':
        return [
            'title'        => 'required|string|max:255',
            'desc'         => 'required|string',
            'category_id'  => 'required|exists:categories,id',
            'comment_able' => 'in:on,off',

            'images'   => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    case 'PUT':
        return [
            'title'        => 'sometimes|required|string|max:255',
            'desc'         => 'sometimes|required|string',
            'category_id'  => 'sometimes|required|exists:categories,id',
            'comment_able' => 'sometimes|in:on,off',

            'images'   => 'sometimes',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    default:
        return [];
}

    }
}

