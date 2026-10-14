<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'empleado_nombre' => ['required', 'string', 'max:255'],
            'empleado_apellido' => ['required', 'string', 'max:255'],
            'empleado_dni' => ['required', 'string', 'max:255', 'unique:users,dni'],
            'empleado_telefono' => ['nullable', 'string', 'max:255'],
            'empleado_correo' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'empleado_contrasena' => ['required', 'string', 'min:8'],
            'rol_id' => ['required', 'integer', Rule::in([3, 4])],
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
            'empleado_nombre.required' => 'El nombre del empleado es obligatorio.',
            'empleado_apellido.required' => 'El apellido del empleado es obligatorio.',
            'empleado_dni.required' => 'El DNI del empleado es obligatorio.',
            'empleado_dni.unique' => 'Ya existe un empleado registrado con ese DNI.',
            'empleado_correo.required' => 'El correo electrónico es obligatorio.',
            'empleado_correo.email' => 'El correo electrónico no tiene un formato válido.',
            'empleado_correo.unique' => 'Ya existe un usuario registrado con ese correo electrónico.',
            'empleado_contrasena.required' => 'La contraseña del empleado es obligatoria.',
            'empleado_contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'rol_id.required' => 'Debés asignar un rol al empleado.',
            'rol_id.in' => 'El rol seleccionado no corresponde a un empleado.',
        ];
    }
}
