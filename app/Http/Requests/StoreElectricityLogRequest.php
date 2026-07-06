<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreElectricityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required', 'exists:items,id'],
            'log_date' => ['required', 'date'],
            'before_kwh' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
            'purchased_kwh' => ['nullable', 'numeric', 'min:0'],
            'after_kwh' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->validated();
            if (isset($data['after_kwh']) && isset($data['before_kwh'])) {
                if ($data['after_kwh'] < $data['before_kwh']) {
                    $validator->errors()->add('after_kwh', 'Sisa kWh setelah isi tidak boleh lebih kecil dari sisa kWh sebelum isi.');
                }
            }
        });
    }
}
