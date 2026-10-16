<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items' => ['required', 'array', 'min:1'],
            'items.*.meal_id' => ['required', 'integer', 'exists:meals,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
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
            'items.required' => 'Tu carrito está vacío.',
            'items.min' => 'Tu carrito debe tener al menos un producto para confirmar el pedido.',
            'items.*.meal_id.required' => 'El producto seleccionado no es válido.',
            'items.*.meal_id.exists' => 'El producto seleccionado no existe.',
            'items.*.quantity.required' => 'Debés indicar la cantidad del producto.',
            'items.*.quantity.integer' => 'La cantidad debe ser un número entero.',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'items.*.quantity.max' => 'La cantidad no puede superar las 99 unidades.',
        ];
    }
}
