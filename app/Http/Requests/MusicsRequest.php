<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'views' => 'required|integer|min:0',
            'youtube_id' => 'required|string|max:255',
            'thumb' => 'required|url',
        ];
    }

    /**
     * Gets the custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => "title",
            'views' => "views",
            'youtube_id' => "youtube_id",
            'thumb' => "thumb",
        ];
    }

    /**
     * Gets error messages for defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "required" => "The ':attribute' field is required.",
        ];
    }
}
