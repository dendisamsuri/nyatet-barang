<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFuelLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'fuel_type_id' => ['required', 'exists:fuel_types,id'],
            'fuel_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'liter' => ['nullable', 'numeric', 'gt:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
