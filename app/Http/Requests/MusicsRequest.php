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
            'youtube_url' => 'required|url',
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
            'youtube_url' => "YouTube URL",
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
            "url" => "The ':attribute' must be a valid URL.",
        ];
    }
}
