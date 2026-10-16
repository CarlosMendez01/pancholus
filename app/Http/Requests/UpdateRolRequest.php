<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolRequest extends FormRequest
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
            'rol_actualizado' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rols', 'role')->ignore($this->route('rol')),
            ],
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
            'rol_actualizado.required' => 'El nombre del rol es obligatorio.',
            'rol_actualizado.string' => 'El nombre del rol debe ser texto.',
            'rol_actualizado.max' => 'El nombre del rol no puede superar los 255 caracteres.',
            'rol_actualizado.unique' => 'Ya existe un rol con ese nombre.',
        ];
    }
}
