<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRolRequest extends FormRequest
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
            'nuevo_rol' => ['required', 'string', 'max:255', 'unique:rols,role'],
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
            'nuevo_rol.required' => 'El nombre del rol es obligatorio.',
            'nuevo_rol.string' => 'El nombre del rol debe ser texto.',
            'nuevo_rol.max' => 'El nombre del rol no puede superar los 255 caracteres.',
            'nuevo_rol.unique' => 'Ya existe un rol con ese nombre.',
        ];
    }
}
