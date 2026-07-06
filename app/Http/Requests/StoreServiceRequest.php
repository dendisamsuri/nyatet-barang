<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'service_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'details' => ['nullable', 'array'],
            'details.*.name' => ['required_with:details', 'string', 'max:150'],
            'details.*.price' => ['required_with:details', 'numeric', 'min:0'],
            'details.*.notes' => ['nullable', 'string'],
        ];
    }
}
