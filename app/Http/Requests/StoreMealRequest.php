<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMealRequest extends FormRequest
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
                Rule::unique('meals', 'name')->whereNull('deleted_at'),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'details' => ['required', 'string', 'max:255'],
            'path' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'filter_id' => ['required', 'integer', 'exists:filters,id'],
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
            'name.required' => 'El nombre de la comida es obligatorio.',
            'name.unique' => 'Ya existe una comida registrada con ese nombre.',
            'price.required' => 'El precio de la comida es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.min' => 'El precio no puede ser negativo.',
            'details.required' => 'La descripción de la comida es obligatoria.',
            'path.required' => 'La foto de la comida es obligatoria.',
            'path.image' => 'El archivo debe ser una imagen.',
            'path.mimes' => 'La foto debe ser un archivo JPG, PNG o WEBP.',
            'path.max' => 'La foto no puede superar los 2 MB.',
            'filter_id.required' => 'Debés seleccionar un filtro para la comida.',
            'filter_id.exists' => 'El filtro seleccionado no existe.',
        ];
    }
}
