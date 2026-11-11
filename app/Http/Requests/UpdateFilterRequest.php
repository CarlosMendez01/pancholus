<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFilterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('filters', 'name')
                    ->ignore($this->route('filter'))
                    ->whereNull('deleted_at'),
            ],
            'return_to' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del filtro es obligatorio.',
            'name.unique' => 'Ya existe un filtro con ese nombre.',
            'name.max' => 'El nombre del filtro no puede superar los 255 caracteres.',
        ];
    }
}
