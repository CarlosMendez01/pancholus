<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza el correo a minúsculas antes de validar.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('correo_actualizado')) {
            $this->merge(['correo_actualizado' => mb_strtolower((string) $this->input('correo_actualizado'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'telefono_actualizado' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+\-\s().xX]{6,25}$/'],
            'correo_actualizado' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user')),
            ],
            'empleado_contrasena' => ['nullable', 'string', 'min:8'],
            'rol_actualizado' => ['required', 'integer', Rule::in([3, 4])],
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
            'correo_actualizado.required' => 'El correo electrónico es obligatorio.',
            'correo_actualizado.email' => 'El correo electrónico no tiene un formato válido.',
            'correo_actualizado.unique' => 'Ya existe un usuario registrado con ese correo electrónico.',
            'telefono_actualizado.regex' => 'El teléfono ingresado no tiene un formato válido.',
            'empleado_contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol_actualizado.required' => 'Debés asignar un rol al empleado.',
            'rol_actualizado.in' => 'El rol seleccionado no corresponde a un empleado.',
        ];
    }
}
