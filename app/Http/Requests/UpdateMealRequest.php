<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMealRequest extends FormRequest
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
                Rule::unique('meals', 'name')
                    ->ignore($this->route('meal'))
                    ->whereNull('deleted_at'),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'details' => ['required', 'string', 'max:255'],
            'path' => ['nullable', 'image', 'max:2048'],
            'filter_id' => ['required', 'integer', 'exists:filters,id'],
        ];
    }
}
